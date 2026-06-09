<?php

namespace App\Http\Controllers;

use App\Exports\RecruitmentApplicationsExport;
use App\Models\Candidate;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Vacancy;
use App\Services\AuditLogger;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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

        $vacancy = Vacancy::create($data);

        AuditLogger::log(
            'vacancy_created',
            'Created vacancy: ' . $vacancy->title,
            $vacancy,
            [
                'vacancy_id' => $vacancy->id,
                'code' => $vacancy->code,
                'status' => $vacancy->status,
            ]
        );

        return redirect()
            ->route('recruitment.index')
            ->with('status', 'Vacancy created.');
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

        AuditLogger::log(
            'candidate_created',
            'Created candidate: ' . $candidate->full_name,
            $candidate,
            [
                'candidate_id' => $candidate->id,
                'email' => $candidate->email,
            ]
        );

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $path = $file->store('candidate-documents', 'public');

            $document = Document::create([
                'documentable_type' => get_class($candidate),
                'documentable_id' => $candidate->id,
                'disk' => 'public',
                'path' => $path,
                'original_name' => 'CV - ' . $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            AuditLogger::log(
                'candidate_cv_uploaded',
                'Uploaded CV for candidate ' . $candidate->full_name,
                $document,
                [
                    'candidate_id' => $candidate->id,
                    'document_id' => $document->id,
                    'path' => $document->path,
                ]
            );
        }

        return redirect()
            ->route('recruitment.index')
            ->with('status', 'Candidate added.');
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

        $application = JobApplication::updateOrCreate(
            [
                'vacancy_id' => $data['vacancy_id'],
                'candidate_id' => $data['candidate_id'],
            ],
            $data
        );

        $application->load(['candidate', 'vacancy']);

        AuditLogger::log(
            'application_recorded',
            'Recorded application for ' . $application->candidate->full_name . ' - ' . $application->vacancy->title,
            $application,
            [
                'application_id' => $application->id,
                'candidate_id' => $application->candidate_id,
                'vacancy_id' => $application->vacancy_id,
                'stage' => $application->stage,
                'status' => $application->status,
            ]
        );

        return redirect()
            ->route('recruitment.index')
            ->with('status', 'Application recorded.');
    }

    public function updateApplicationStage(Request $request, JobApplication $application)
    {
        $data = $request->validate([
            'stage' => ['required', 'in:applied,screening,interview,offer,hired,rejected'],
            'status' => ['required', 'in:active,hired,rejected,withdrawn'],
        ]);

        $oldStage = $application->stage;
        $oldStatus = $application->status;

        $application->update($data);
        $application->load(['candidate', 'vacancy']);

        AuditLogger::log(
            'application_updated',
            'Updated application for ' . $application->candidate->full_name,
            $application,
            [
                'application_id' => $application->id,
                'old_stage' => $oldStage,
                'new_stage' => $application->stage,
                'old_status' => $oldStatus,
                'new_status' => $application->status,
            ]
        );

        return redirect()
            ->route('recruitment.index')
            ->with('status', 'Application updated.');
    }

    public function generateOfferLetter(JobApplication $application)
    {
        $application->load(['candidate', 'vacancy']);

        $candidate = $application->candidate;
        $vacancy = $application->vacancy;

        $pdf = DomPdf::loadView('recruitment.offer-letter', [
            'application' => $application,
            'candidate' => $candidate,
            'vacancy' => $vacancy,
        ]);

        $safeCandidateName = str_replace(['/', '\\', ' '], '-', $candidate->full_name);
        $fileName = 'Offer-Letter-' . $safeCandidateName . '-' . now()->format('YmdHis') . '.pdf';
        $path = 'candidate-documents/offers/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        $document = Document::create([
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

        \App\Models\HrNotification::create([
            'user_id' => auth()->id(),
            'type' => 'success',
            'title' => 'Offer letter generated',
            'message' => 'Offer letter generated for ' . $candidate->full_name . '.',
            'url' => route('recruitment.index'),
        ]);

        AuditLogger::log(
            'offer_letter_generated',
            'Generated offer letter for ' . $candidate->full_name,
            $application,
            [
                'candidate_id' => $candidate->id,
                'vacancy_id' => $vacancy->id,
                'application_id' => $application->id,
                'document_id' => $document->id,
                'path' => $document->path,
            ]
        );

        return redirect()
            ->route('recruitment.index')
            ->with('status', 'Offer letter generated successfully.');
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

        AuditLogger::log(
            'candidate_converted_to_employee',
            'Converted candidate ' . $candidate->full_name . ' to employee ' . $employee->employee_number,
            $application,
            [
                'candidate_id' => $candidate->id,
                'employee_id' => $employee->id,
                'application_id' => $application->id,
            ]
        );

        return redirect()
            ->route('recruitment.index')
            ->with('status', 'Candidate converted to employee and documents moved successfully.');
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

    public function exportApplications()
    {
        AuditLogger::log(
            'recruitment_report_exported',
            'Exported recruitment applications report',
            null,
            []
        );

        return Excel::download(
            new RecruitmentApplicationsExport,
            'recruitment-applications.xlsx'
        );
    }
}