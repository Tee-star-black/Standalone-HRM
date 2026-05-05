<?php

namespace App\Http\Controllers;

use App\Models\Document;
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

        $documents = Document::where('documentable_type', get_class($employee))
            ->where('documentable_id', $employee->id)
            ->latest()
            ->get();

        return view('my-documents.index', [
            'employee' => $employee,
            'documents' => $documents,
        ]);
    }

    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $data = $request->validate([
            'document_type' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('file');
        $path = $file->store('employee-documents', 'public');

        Document::create([
            'documentable_type' => get_class($employee),
            'documentable_id' => $employee->id,
            'disk' => 'public',
            'path' => $path,
            'original_name' => $data['document_type'] . ' - ' . $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()
            ->route('my-documents.index')
            ->with('status', 'Document uploaded successfully.');
    }

    public function preview(Document $document)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        if (
            $document->documentable_type !== get_class($employee) ||
            (int) $document->documentable_id !== (int) $employee->id
        ) {
            abort(403, 'You can only preview your own documents.');
        }

        if (! Storage::disk($document->disk)->exists($document->path)) {
            abort(404, 'File not found.');
        }

        return view('my-documents.preview', [
            'document' => $document,
            'url' => route('my-documents.file', $document),
        ]);
    }

    public function file(Document $document)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        if (
            $document->documentable_type !== get_class($employee) ||
            (int) $document->documentable_id !== (int) $employee->id
        ) {
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

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        if (
            $document->documentable_type !== get_class($employee) ||
            (int) $document->documentable_id !== (int) $employee->id
        ) {
            abort(403, 'You can only access your own documents.');
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

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        if (
            $document->documentable_type !== get_class($employee) ||
            (int) $document->documentable_id !== (int) $employee->id
        ) {
            abort(403, 'You can only delete your own documents.');
        }

        if (Storage::disk($document->disk)->exists($document->path)) {
            Storage::disk($document->disk)->delete($document->path);
        }

        $document->delete();

        return redirect()
            ->route('my-documents.index')
            ->with('status', 'Document deleted successfully.');
    }
}