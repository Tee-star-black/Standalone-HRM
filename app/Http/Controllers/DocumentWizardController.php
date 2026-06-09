<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CompanySetting;
use App\Models\Employee;
use App\Models\GeneratedDocument;
use App\Services\AuditLogger;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentWizardController extends Controller
{
    public function index()
    {
        return view('document-wizard.index', [
            'employees' => Employee::orderBy('first_name')->get(),
            'candidates' => Candidate::orderBy('first_name')->get(),
            'company' => CompanySetting::current(),
            'documentTypes' => [
                'offer_letter' => 'Offer Letter',
                'salary_confirmation' => 'Salary Confirmation Letter',
                'employment_contract' => 'Employment Contract',
                'supervising_doctor_contract' => 'Supervising Doctor Contract',
                'practice_manager_contract' => 'Practice Manager Contract',
                'clinical_associate_contract' => 'Clinical Associate Contract',
                'policy_acknowledgement' => 'Policy Acknowledgement',
                'payslip' => 'Payslip',
            ],
        ]);
    }

    public function generate(Request $request)
    {
        $data = $request->validate([
            'document_type' => ['required', 'string'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'candidate_id' => ['nullable', 'exists:candidates,id'],

            'position_title' => ['nullable', 'string'],
            'start_date' => ['nullable', 'string'],
            'salary' => ['nullable', 'string'],
            'manager_name' => ['nullable', 'string'],
            'offer_expiry_date' => ['nullable', 'string'],
            'candidate_address' => ['nullable', 'string'],

            'purpose' => ['nullable', 'string'],
            'recipient_name' => ['nullable', 'string'],

            'working_hours' => ['nullable', 'string'],
            'probation_period' => ['nullable', 'string'],

            'policy_name' => ['nullable', 'string'],

            'pay_period' => ['nullable', 'string'],
            'basic_salary' => ['nullable', 'numeric'],
            'overtime' => ['nullable', 'numeric'],
            'allowance' => ['nullable', 'numeric'],
            'bonus' => ['nullable', 'numeric'],
            'paye' => ['nullable', 'numeric'],
            'uif' => ['nullable', 'numeric'],
            'medical_aid' => ['nullable', 'numeric'],
            'loan_deduction' => ['nullable', 'numeric'],
        ]);

        $employee = !empty($data['employee_id']) ? Employee::find($data['employee_id']) : null;
        $candidate = !empty($data['candidate_id']) ? Candidate::find($data['candidate_id']) : null;
        $company = CompanySetting::current();

        $earnings = [
            'basic_salary' => (float) ($data['basic_salary'] ?? 0),
            'overtime' => (float) ($data['overtime'] ?? 0),
            'allowance' => (float) ($data['allowance'] ?? 0),
            'bonus' => (float) ($data['bonus'] ?? 0),
        ];

        $deductions = [
            'paye' => (float) ($data['paye'] ?? 0),
            'uif' => (float) ($data['uif'] ?? 0),
            'medical_aid' => (float) ($data['medical_aid'] ?? 0),
            'loan_deduction' => (float) ($data['loan_deduction'] ?? 0),
        ];

        $gross = array_sum($earnings);
        $totalDeductions = array_sum($deductions);
        $netPay = $gross - $totalDeductions;

       $pdf = DomPdf::loadView('document-wizard.pdf', [
            'documentType' => $data['document_type'],
            'data' => $data,
            'employee' => $employee,
            'candidate' => $candidate,
            'company' => $company,
            'earnings' => $earnings,
            'deductions' => $deductions,
            'gross' => $gross,
            'totalDeductions' => $totalDeductions,
            'netPay' => $netPay,
        ]);

        $owner = $employee;
        $employeeId = $employee?->id;

        if (! $employeeId) {
            $employeeId = Auth::user()->employee?->id;
        }

        if (! $employeeId) {
            abort(403, 'No employee profile available to attach this generated document.');
        }

        $fileName = str($data['document_type'])->slug() . '-' . now()->format('YmdHis') . '.pdf';
        $path = 'generated-documents/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        $generatedDocument = GeneratedDocument::create([
            'employee_id' => $employeeId,
            'generated_by' => Auth::id(),
            'type' => $data['document_type'],
            'title' => str_replace('_', ' ', ucfirst($data['document_type'])),
            'disk' => 'public',
            'path' => $path,
        ]);

        AuditLogger::log(
            'document_wizard_generated',
            'Generated document using wizard: ' . $data['document_type'],
            $generatedDocument,
            [
                'document_type' => $data['document_type'],
                'employee_id' => $employee?->id,
                'candidate_id' => $candidate?->id,
                'path' => $path,
            ]
        );

        return redirect()
            ->route('generated-documents.index')
            ->with('status', 'Document generated successfully.');
    }
}