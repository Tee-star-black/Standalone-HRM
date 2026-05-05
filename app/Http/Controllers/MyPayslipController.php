<?php

namespace App\Http\Controllers;

use App\Models\Payslip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class MyPayslipController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        return view('my-payslips.index', [
            'employee' => $employee,
            'payslips' => $employee->payslips()
                ->where('status', 'published')
                ->latest()
                ->get(),
        ]);
    }

    public function show(Payslip $payslip)
    {
        $employee = Auth::user()->employee;

        if (! $employee || (int) $payslip->employee_id !== (int) $employee->id) {
            abort(403, 'You can only view your own payslips.');
        }

        if ($payslip->status !== 'published') {
            abort(403, 'This payslip is not published yet.');
        }

        return view('my-payslips.show', [
            'payslip' => $payslip->load('employee'),
        ]);
    }

    public function download(Payslip $payslip)
    {
        $employee = Auth::user()->employee;

        if (! $employee || (int) $payslip->employee_id !== (int) $employee->id) {
            abort(403, 'You can only download your own payslips.');
        }

        if ($payslip->status !== 'published') {
            abort(403, 'This payslip is not published yet.');
        }

        $pdf = Pdf::loadView('my-payslips.pdf', [
            'payslip' => $payslip->load('employee'),
        ]);

        $safePeriod = str_replace(['/', '\\'], '-', $payslip->period);

        return $pdf->download('Payslip-' . $safePeriod . '.pdf');
    }
}