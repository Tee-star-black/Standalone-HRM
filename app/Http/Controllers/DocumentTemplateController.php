<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\DocumentTemplate;
use App\Models\Employee;
use App\Models\GeneratedDocument;
use App\Services\AuditLogger;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentTemplateController extends Controller
{
    public function index()
    {
        return view('document-templates.index', [
            'templates' => DocumentTemplate::latest()->get(),
            'company' => CompanySetting::current(),
            'employees' => Employee::orderBy('first_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'fields' => ['nullable', 'string'],
            'template_file' => ['nullable', 'file', 'mimes:doc,docx', 'max:10240'],
        ]);

        $fields = collect(explode(',', $data['fields'] ?? ''))
            ->map(fn ($field) => trim($field))
            ->filter()
            ->values()
            ->toArray();

        $filePath = null;
        $fileOriginalName = null;
        $fileMimeType = null;

        if ($request->hasFile('template_file')) {
            $file = $request->file('template_file');
            $filePath = $file->store('document-templates/originals', 'public');
            $fileOriginalName = $file->getClientOriginalName();
            $fileMimeType = $file->getClientMimeType();
        }

        $template = DocumentTemplate::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
            'content' => $data['content'] ?? '',
            'file_path' => $filePath,
            'file_original_name' => $fileOriginalName,
            'file_mime_type' => $fileMimeType,
            'fields' => $fields,
            'is_active' => true,
        ]);

        AuditLogger::log(
            'document_template_created',
            'Created document template: ' . $template->name,
            $template,
            [
                'template_id' => $template->id,
                'file_uploaded' => (bool) $template->file_path,
                'file_original_name' => $template->file_original_name,
            ]
        );

        return back()->with('status', 'Document template created.');
    }

    public function updateCompanySettings(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'company_logo' => ['nullable', 'image', 'max:2048'],
            'ceo_name' => ['nullable', 'string', 'max:255'],
            'ceo_title' => ['nullable', 'string', 'max:255'],
            'company_address' => ['nullable', 'string'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:255'],
            'ceo_signature' => ['nullable', 'image', 'max:2048'],
        ]);

        $company = CompanySetting::current();

        if ($request->hasFile('company_logo')) {
            $data['company_logo_path'] = $request->file('company_logo')
                ->store('company-logos', 'public');
        }

        if ($request->hasFile('ceo_signature')) {
            $data['ceo_signature_path'] = $request->file('ceo_signature')
                ->store('company-signatures', 'public');
        }

        unset($data['company_logo'], $data['ceo_signature']);

        $company->update($data);

        AuditLogger::log(
            'company_settings_updated',
            'Updated company document settings',
            $company
        );

        return back()->with('status', 'Company settings updated.');
    }

    public function generate(Request $request, DocumentTemplate $template)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'custom_fields' => ['nullable', 'array'],
        ]);

        $employee = Employee::findOrFail($data['employee_id']);
        $company = CompanySetting::current();

        $values = $this->replacementValues($employee, $company, $data['custom_fields'] ?? []);

        if ($template->file_path && Storage::disk('public')->exists($template->file_path)) {
            return $this->generateFromDocxTemplate($template, $employee, $company, $values);
        }

        return $this->generateFromTextTemplate($template, $employee, $company, $values);
    }

    private function generateFromDocxTemplate(DocumentTemplate $template, Employee $employee, CompanySetting $company, array $values)
    {
        $templatePath = Storage::disk('public')->path($template->file_path);

        $processor = new TemplateProcessor($templatePath);

        foreach ($values as $key => $value) {
            $processor->setValue($key, $value);
        }

        if ($company->ceo_signature_path && Storage::disk('public')->exists($company->ceo_signature_path)) {
            try {
                $processor->setImageValue('ceo_signature', [
                    'path' => Storage::disk('public')->path($company->ceo_signature_path),
                    'width' => 150,
                    'height' => 60,
                    'ratio' => true,
                ]);
            } catch (\Throwable $e) {
                //
            }
        }

        if ($company->company_logo_path && Storage::disk('public')->exists($company->company_logo_path)) {
            try {
                $processor->setImageValue('company_logo', [
                    'path' => Storage::disk('public')->path($company->company_logo_path),
                    'width' => 150,
                    'height' => 70,
                    'ratio' => true,
                ]);
            } catch (\Throwable $e) {
                //
            }
        }

        $fileName = str($template->name)->slug() . '-' . $employee->employee_number . '-' . now()->format('YmdHis') . '.docx';
        $relativePath = 'generated-documents/' . $fileName;
        $absolutePath = Storage::disk('public')->path($relativePath);

        if (! is_dir(dirname($absolutePath))) {
            mkdir(dirname($absolutePath), 0775, true);
        }

        $processor->saveAs($absolutePath);

        $generatedDocument = GeneratedDocument::create([
            'employee_id' => $employee->id,
            'generated_by' => Auth::id(),
            'type' => $template->type,
            'title' => $template->name . ' (DOCX)',
            'disk' => 'public',
            'path' => $relativePath,
        ]);

        AuditLogger::log(
            'template_docx_generated',
            'Generated DOCX document from template: ' . $template->name,
            $generatedDocument,
            [
                'template_id' => $template->id,
                'employee_id' => $employee->id,
                'path' => $relativePath,
            ]
        );

        return redirect()
            ->route('generated-documents.index')
            ->with('status', 'DOCX document generated successfully.');
    }

    private function generateFromTextTemplate(DocumentTemplate $template, Employee $employee, CompanySetting $company, array $values)
    {
        $content = $template->content;

        foreach ($values as $key => $value) {
            $content = str_replace('{{ ' . $key . ' }}', $value, $content);
            $content = str_replace('{{' . $key . '}}', $value, $content);
            $content = str_replace('${' . $key . '}', $value, $content);
        }

        $pdf = DomPdf::loadView('document-templates.pdf', [
            'content' => $content,
            'company' => $company,
            'employee' => $employee,
            'template' => $template,
        ]);

        $fileName = str($template->name)->slug() . '-' . $employee->employee_number . '-' . now()->format('YmdHis') . '.pdf';
        $path = 'generated-documents/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        $generatedDocument = GeneratedDocument::create([
            'employee_id' => $employee->id,
            'generated_by' => Auth::id(),
            'type' => $template->type,
            'title' => $template->name . ' (PDF)',
            'disk' => 'public',
            'path' => $path,
        ]);

        AuditLogger::log(
            'template_pdf_generated',
            'Generated PDF document from text template: ' . $template->name,
            $generatedDocument,
            [
                'template_id' => $template->id,
                'employee_id' => $employee->id,
                'path' => $path,
            ]
        );

        return redirect()
            ->route('generated-documents.index')
            ->with('status', 'PDF document generated successfully.');
    }

    private function replacementValues(Employee $employee, CompanySetting $company, array $customFields = []): array
    {
        $values = [
            'employee_name' => $employee->full_name,
            'employee_number' => $employee->employee_number,
            'employee_email' => $employee->email,
            'employee_phone' => $employee->phone ?? '',
            'job_title' => $employee->job_title ?? '',
            'employment_type' => $employee->employment_type ?? '',
            'hire_date' => $employee->hire_date?->format('d M Y') ?? '',
            'company_name' => $company->company_name ?? '',
            'company_address' => $company->company_address ?? '',
            'company_email' => $company->company_email ?? '',
            'company_phone' => $company->company_phone ?? '',
            'ceo_name' => $company->ceo_name ?? '',
            'ceo_title' => $company->ceo_title ?? '',
            'today' => now()->format('d M Y'),
        ];

        foreach ($customFields as $key => $value) {
            $values[$key] = $value ?? '';
        }

        return $values;
    }

    public function downloadTemplate(DocumentTemplate $template)
    {
        if (! $template->file_path || ! Storage::disk('public')->exists($template->file_path)) {
            abort(404, 'Template file not found.');
        }

        return Storage::disk('public')->download(
            $template->file_path,
            $template->file_original_name ?: $template->name . '.docx'
        );
    }

    public function destroy(DocumentTemplate $template)
    {
        AuditLogger::log(
            'document_template_deleted',
            'Deleted document template: ' . $template->name,
            $template,
            [
                'template_id' => $template->id,
                'file_path' => $template->file_path,
            ]
        );

        if ($template->file_path && Storage::disk('public')->exists($template->file_path)) {
            Storage::disk('public')->delete($template->file_path);
        }

        $template->delete();

        return back()->with('status', 'Document template deleted.');
    }
}