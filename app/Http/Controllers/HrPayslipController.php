<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use App\Models\Payslip;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HrPayslipController extends Controller
{
    public function index()
    {
        return view('hr-payslips.index', [
            'employees' => Employee::orderBy('first_name')->get(),
            'payslips' => Payslip::with('employee')->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'allowances' => ['nullable', 'numeric', 'min:0'],
            'deductions' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,published'],
            'notes' => ['nullable', 'string'],
        ]);

        $basic = (float) $data['basic_salary'];
        $allowances = (float) ($data['allowances'] ?? 0);
        $deductions = (float) ($data['deductions'] ?? 0);
        $tax = (float) ($data['tax'] ?? 0);

        $data['net_pay'] = $basic + $allowances - $deductions - $tax;

        $payslip = Payslip::updateOrCreate(
            [
                'employee_id' => $data['employee_id'],
                'year' => $data['year'],
                'month' => $data['month'],
            ],
            $data
        );

        if ($payslip->status === 'published') {
            $this->savePayslipPdfAsDocument($payslip);
        }

        return redirect()
            ->route('hr-payslips.index')
            ->with('status', 'Payslip saved successfully.');
    }

    public function publish(Payslip $payslip)
    {
        $payslip->update(['status' => 'published']);

        $this->savePayslipPdfAsDocument($payslip);

        return redirect()
            ->route('hr-payslips.index')
            ->with('status', 'Payslip published and saved to employee documents.');
    }

    private function savePayslipPdfAsDocument(Payslip $payslip): void
    {
        $payslip->load('employee');

        $safePeriod = str_replace(['/', '\\'], '-', $payslip->period);

        $fileName = 'Payslip-' . $payslip->employee->employee_number . '-' . $safePeriod . '.pdf';
        $path = 'employee-documents/payslips/' . $fileName;

        $pdf = DomPDF::loadView('my-payslips.pdf', [
            'payslip' => $payslip,
        ]);

        Storage::disk('public')->put($path, $pdf->output());

        Document::updateOrCreate(
            [
                'documentable_type' => get_class($payslip->employee),
                'documentable_id' => $payslip->employee->id,
                'original_name' => 'Payslip - ' . $payslip->period,
            ],
            [
                'disk' => 'public',
                'path' => $path,
                'mime_type' => 'application/pdf',
                'size' => Storage::disk('public')->size($path),
            ]
        );
    }
}