<?php

namespace App\Http\Controllers;

use App\Models\GeneratedDocument;
use App\Services\AuditLogger;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GeneratedDocumentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasAnyRole(['Super Admin', 'HR Admin'])) {
            $documents = GeneratedDocument::with(['employee', 'generator'])
                ->latest()
                ->get();

            return view('generated-documents.index', [
                'employee' => null,
                'documents' => $documents,
                'isHrView' => true,
            ]);
        }

        $employee = $user->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        return view('generated-documents.index', [
            'employee' => $employee,
            'documents' => $employee->generatedDocuments()->latest()->get(),
            'isHrView' => false,
        ]);
    }

    public function generateEmploymentConfirmation()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $pdf = DomPdf::loadView('generated-documents.templates.employment-confirmation', [
            'employee' => $employee,
            'user' => Auth::user(),
        ]);

        $fileName = 'employment-confirmation-' . $employee->employee_number . '-' . now()->format('YmdHis') . '.pdf';
        $path = 'generated-documents/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        $generatedDocument = GeneratedDocument::create([
            'employee_id' => $employee->id,
            'generated_by' => Auth::id(),
            'type' => 'employment_confirmation',
            'title' => 'Employment Confirmation Letter',
            'disk' => 'public',
            'path' => $path,
        ]);

        AuditLogger::log(
            'generated_document_created',
            'Generated document: ' . $generatedDocument->title,
            $generatedDocument,
            [
                'generated_document_id' => $generatedDocument->id,
                'employee_id' => $generatedDocument->employee_id,
                'path' => $generatedDocument->path,
            ]
        );

        return redirect()
            ->route('generated-documents.index')
            ->with('status', 'Employment confirmation letter generated.');
    }

    public function download(GeneratedDocument $generatedDocument)
    {
        $user = Auth::user();

        if (! $user->hasAnyRole(['Super Admin', 'HR Admin'])) {
            $employee = $user->employee;

            if (! $employee || (int) $generatedDocument->employee_id !== (int) $employee->id) {
                abort(403, 'You can only download your own generated documents.');
            }
        }

        if (! Storage::disk($generatedDocument->disk)->exists($generatedDocument->path)) {
            abort(404, 'File not found.');
        }

        $extension = pathinfo($generatedDocument->path, PATHINFO_EXTENSION) ?: 'pdf';
        $safeName = str_replace(['/', '\\'], '-', $generatedDocument->title) . '.' . $extension;

        return Storage::disk($generatedDocument->disk)->download($generatedDocument->path, $safeName);
    }
}