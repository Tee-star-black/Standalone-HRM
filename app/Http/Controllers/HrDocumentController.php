<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HrDocumentController extends Controller
{
    public function index()
    {
        return view('hr-documents.index', [
            'employees' => Employee::orderBy('first_name')->get(),
            'documents' => Document::with('documentable')
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'document' => ['required', 'file', 'max:10240'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $employee = Employee::findOrFail($data['employee_id']);

        $file = $request->file('document');
        $path = $file->store('employee-documents', 'public');

        $document = Document::create([
            'documentable_type' => get_class($employee),
            'documentable_id' => $employee->id,
            'disk' => 'public',
            'path' => $path,
            'original_name' => $data['title'] ?: $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        AuditLogger::log(
            'hr_document_uploaded',
            'HR uploaded document for ' . $employee->full_name . ': ' . $document->original_name,
            $document,
            [
                'document_id' => $document->id,
                'employee_id' => $employee->id,
                'path' => $document->path,
            ]
        );

        return redirect()
            ->route('hr-documents.index')
            ->with('status', 'Document uploaded successfully.');
    }

    public function preview(Document $document)
    {
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

    public function download(Document $document)
    {
        if (! Storage::disk($document->disk)->exists($document->path)) {
            abort(404, 'File not found.');
        }

        $safeName = str_replace(['/', '\\'], '-', $document->original_name);

        return Storage::disk($document->disk)->download($document->path, $safeName);
    }

    public function destroy(Document $document)
    {
        AuditLogger::log(
            'hr_document_deleted',
            'HR deleted document: ' . $document->original_name,
            $document,
            [
                'document_id' => $document->id,
                'owner_type' => $document->documentable_type,
                'owner_id' => $document->documentable_id,
                'path' => $document->path,
            ]
        );

        if (Storage::disk($document->disk)->exists($document->path)) {
            Storage::disk($document->disk)->delete($document->path);
        }

        $document->delete();

        return redirect()
            ->route('hr-documents.index')
            ->with('status', 'Document deleted successfully.');
    }
}