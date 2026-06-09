<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use App\Models\Payslip;
use App\Services\AuditLogger;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HrPayslipController extends Controller
{
    public function index()
    {
        return view('hr-payslips.index', [
            'employees' => Employee::orderBy('first_name')->get(),
            'payslips' => Payslip::with('employee')
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'year' => ['required', 'integer', 'min:2000'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'allowances' => ['nullable', 'numeric', 'min:0'],
            'deductions' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $basic = $data['basic_salary'];
        $allowances = $data['allowances'] ?? 0;
        $deductions = $data['deductions'] ?? 0;
        $tax = $data['tax'] ?? 0;

        $data['allowances'] = $allowances;
        $data['deductions'] = $deductions;
        $data['tax'] = $tax;
        $data['net_pay'] = $basic + $allowances - $deductions - $tax;
        $data['status'] = 'draft';

        Payslip::create($data);

        return redirect()
            ->route('hr-payslips.index')
            ->with('status', 'Payslip created as draft.');
    }

    public function publish(Payslip $payslip)
    {
        $payslip->load('employee');

        $payslip->update([
            'status' => 'published',
        ]);

        $pdf = DomPdf::loadView('hr-payslips.pdf', [
            'payslip' => $payslip,
            'employee' => $payslip->employee,
        ]);

        $safePeriod = str_pad($payslip->month, 2, '0', STR_PAD_LEFT) . '-' . $payslip->year;
        $fileName = 'Payslip-' . $payslip->employee->employee_number . '-' . $safePeriod . '.pdf';
        $path = 'employee-documents/payslips/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());

        Document::create([
            'documentable_type' => get_class($payslip->employee),
            'documentable_id' => $payslip->employee->id,
            'disk' => 'public',
            'path' => $path,
            'original_name' => 'Payslip - ' . str_pad($payslip->month, 2, '0', STR_PAD_LEFT) . '/' . $payslip->year,
            'mime_type' => 'application/pdf',
            'size' => Storage::disk('public')->size($path),
        ]);

        AuditLogger::log(
            'payslip_published',
            'Published payslip for ' . $payslip->employee->full_name,
            $payslip,
            [
                'employee_id' => $payslip->employee_id,
                'payslip_id' => $payslip->id,
                'period' => str_pad($payslip->month, 2, '0', STR_PAD_LEFT) . '/' . $payslip->year,
                'net_pay' => $payslip->net_pay,
            ]
        );

        return redirect()
            ->route('hr-payslips.index')
            ->with('status', 'Payslip published, saved to employee documents, and audit logged.');
    }
}