<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyDocumentController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        return view('my-documents.index', [
            'employee' => $employee,
            'documents' => $employee->documents()->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $request->validate([
            'document' => ['required', 'file', 'max:10240'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('document');
        $path = $file->store('employee-documents', 'public');

        $document = Document::create([
            'documentable_type' => get_class($employee),
            'documentable_id' => $employee->id,
            'disk' => 'public',
            'path' => $path,
            'original_name' => $request->title ?: $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        AuditLogger::log(
            'document_uploaded',
            'Employee uploaded document: ' . $document->original_name,
            $document,
            [
                'document_id' => $document->id,
                'employee_id' => $employee->id,
                'path' => $document->path,
            ]
        );

        return redirect()
            ->route('my-documents.index')
            ->with('status', 'Document uploaded successfully.');
    }

    public function preview(Document $document)
    {
        $employee = Auth::user()->employee;

        if (! $employee || $document->documentable_type !== get_class($employee) || (int) $document->documentable_id !== (int) $employee->id) {
            abort(403, 'You can only preview your own documents.');
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

    public function download(Document $document)
    {
        $employee = Auth::user()->employee;

        if (! $employee || $document->documentable_type !== get_class($employee) || (int) $document->documentable_id !== (int) $employee->id) {
            abort(403, 'You can only download your own documents.');
        }

        if (! Storage::disk($document->disk)->exists($document->path)) {
            abort(404, 'File not found.');
        }

        $safeName = str_replace(['/', '\\'], '-', $document->original_name);

        return Storage::disk($document->disk)->download($document->path, $safeName);
    }

    public function destroy(Document $document)
    {
        $employee = Auth::user()->employee;

        if (! $employee || $document->documentable_type !== get_class($employee) || (int) $document->documentable_id !== (int) $employee->id) {
            abort(403, 'You can only delete your own documents.');
        }

        if (Storage::disk($document->disk)->exists($document->path)) {
            Storage::disk($document->disk)->delete($document->path);
        }

        AuditLogger::log(
            'document_deleted',
            'Employee deleted document: ' . $document->original_name,
            $document,
            [
                'document_id' => $document->id,
                'employee_id' => $employee->id,
            ]
        );

        $document->delete();

        return redirect()
            ->route('my-documents.index')
            ->with('status', 'Document deleted successfully.');
    }
}