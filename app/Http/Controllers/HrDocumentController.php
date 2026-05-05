<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;

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
            'document_type' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $employee = Employee::findOrFail($data['employee_id']);

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
            ->route('hr-documents.index')
            ->with('status', 'Document uploaded for employee.');
    }
}