<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Vacancy;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecruitmentController extends Controller
{
    public function index()
    {
        return view('recruitment.index', [
            'jobs' => Job::orderBy('title')->get(),
            'vacancies' => Vacancy::withCount('applications')->latest()->get(),
            'candidates' => Candidate::with('documents')->latest()->get(),
            'applications' => JobApplication::with([
                'candidate.documents',
                'vacancy',
                'convertedEmployee',
            ])->latest()->get(),
        ]);
    }

    public function storeVacancy(Request $request)
    {
        $data = $request->validate([
            'job_id' => ['nullable', 'exists:hr_jobs,id'],
            'title' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:vacancies,code'],
            'description' => ['nullable', 'string'],
            'department' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'max:255'],
            'open_positions' => ['required', 'integer', 'min:1'],
            'closing_date' => ['nullable', 'date'],
            'status' => ['required', 'in:open,on_hold,closed'],
        ]);

        Vacancy::create($data);

        return redirect()->route('recruitment.index')->with('status', 'Vacancy created.');
    }

    public function storeCandidate(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:candidates,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'cv' => ['nullable', 'file', 'max:10240'],
        ]);

        $candidate = Candidate::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'source' => $data['source'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $path = $file->store('candidate-documents', 'public');

            Document::create([
                'documentable_type' => get_class($candidate),
                'documentable_id' => $candidate->id,
                'disk' => 'public',
                'path' => $path,
                'original_name' => 'CV - ' . $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return redirect()->route('recruitment.index')->with('status', 'Candidate added.');
    }

    public function storeApplication(Request $request)
    {
        $data = $request->validate([
            'vacancy_id' => ['required', 'exists:vacancies,id'],
            'candidate_id' => ['required', 'exists:candidates,id'],
            'stage' => ['required', 'in:applied,screening,interview,offer,hired,rejected'],
            'status' => ['required', 'in:active,hired,rejected,withdrawn'],
            'score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'cover_letter' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['applied_at'] = now();

        JobApplication::updateOrCreate(
            [
                'vacancy_id' => $data['vacancy_id'],
                'candidate_id' => $data['candidate_id'],
            ],
            $data
        );

        return redirect()->route('recruitment.index')->with('status', 'Application recorded.');
    }

    public function updateApplicationStage(Request $request, JobApplication $application)
    {
        $data = $request->validate([
            'stage' => ['required', 'in:applied,screening,interview,offer,hired,rejected'],
            'status' => ['required', 'in:active,hired,rejected,withdrawn'],
        ]);

        $application->update($data);

        return redirect()->route('recruitment.index')->with('status', 'Application updated.');
    }

    public function hire(JobApplication $application)
    {
        $application->load(['candidate.documents', 'vacancy', 'convertedEmployee']);

        if ($application->converted_employee_id) {
            return redirect()
                ->route('recruitment.index')
                ->with('status', 'This candidate has already been converted to employee #' . $application->converted_employee_id . '.');
        }

        if ($application->status !== 'hired' && $application->stage !== 'hired') {
            return redirect()
                ->route('recruitment.index')
                ->with('status', 'Candidate must be marked as hired before conversion.');
        }

        $candidate = $application->candidate;

        $existingEmployee = Employee::where('email', $candidate->email)->first();

        if ($existingEmployee) {
            $employee = $existingEmployee;
        } else {
            $employeeNumber = 'EMP' . str_pad((string) (Employee::count() + 1), 3, '0', STR_PAD_LEFT);

            $employee = Employee::create([
                'employee_number' => $employeeNumber,
                'first_name' => $candidate->first_name,
                'last_name' => $candidate->last_name,
                'email' => $candidate->email,
                'phone' => $candidate->phone,
                'status' => 'active',
                'employment_type' => $application->vacancy->employment_type,
                'job_title' => $application->vacancy->title,
                'hire_date' => now()->toDateString(),
            ]);
        }

        foreach ($candidate->documents as $document) {
            $document->update([
                'documentable_type' => get_class($employee),
                'documentable_id' => $employee->id,
                'original_name' => 'Recruitment - ' . $document->original_name,
            ]);
        }

        $application->update([
            'stage' => 'hired',
            'status' => 'hired',
            'converted_employee_id' => $employee->id,
            'converted_at' => now(),
            'notes' => trim(($application->notes ?? '') . "\nConverted to employee ID: " . $employee->id),
        ]);

        return redirect()
            ->route('recruitment.index')
            ->with('status', 'Candidate converted to employee and documents moved successfully.');
    }

    public function generateOfferLetter(JobApplication $application)
    {
        $application->load(['candidate', 'vacancy']);

        $candidate = $application->candidate;

        $pdf = DomPdf::loadView('recruitment.offer-letter', [
            'application' => $application,
            'candidate' => $candidate,
            'vacancy' => $application->vacancy,
        ]);

        $safeName = str_replace(['/', '\\', ' '], '-', 'Offer-Letter-' . $candidate->full_name . '-' . now()->format('Y-m-d')) . '.pdf';
        $path = 'candidate-documents/offers/' . $safeName;

        Storage::disk('public')->put($path, $pdf->output());

        Document::create([
            'documentable_type' => get_class($candidate),
            'documentable_id' => $candidate->id,
            'disk' => 'public',
            'path' => $path,
            'original_name' => 'Offer Letter - ' . $candidate->full_name,
            'mime_type' => 'application/pdf',
            'size' => Storage::disk('public')->size($path),
        ]);

        $application->update([
            'stage' => 'offer',
            'offer_letter_generated_at' => now(),
        ]);

        return redirect()
            ->route('recruitment.index')
            ->with('status', 'Offer letter generated and saved under candidate documents.');
    }

    public function candidateFile(Document $document)
    {
        if (! str_contains($document->documentable_type, 'Candidate')) {
            abort(403, 'This is not a candidate document.');
        }

        if (! Storage::disk($document->disk)->exists($document->path)) {
            abort(404, 'File not found.');
        }

        $fullPath = Storage::disk($document->disk)->path($document->path);
        $safeName = str_replace(['/', '\\'], '-', $document->original_name);

        return response()->make(file_get_contents($fullPath), 200, [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => 'inline; filename="' . $safeName . '"',
        ]);
    }

    public function candidateDownload(Document $document)
    {
        if (! str_contains($document->documentable_type, 'Candidate')) {
            abort(403, 'This is not a candidate document.');
        }

        if (! Storage::disk($document->disk)->exists($document->path)) {
            abort(404, 'File not found.');
        }

        $safeName = str_replace(['/', '\\'], '-', $document->original_name);

        return Storage::disk($document->disk)->download($document->path, $safeName);
    }
}