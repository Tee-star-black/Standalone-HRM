<?php

namespace App\Exports;

use App\Models\Payslip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayrollExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Payslip::with('employee')
            ->latest()
            ->get()
            ->map(function ($payslip) {
                return [
                    'Employee No' => $payslip->employee->employee_number ?? '-',
                    'Employee' => $payslip->employee->full_name ?? '-',
                    'Year' => $payslip->year,
                    'Month' => $payslip->month,
                    'Period' => $payslip->period,
                    'Basic Salary' => $payslip->basic_salary,
                    'Allowances' => $payslip->allowances,
                    'Deductions' => $payslip->deductions,
                    'Tax' => $payslip->tax,
                    'Net Pay' => $payslip->net_pay,
                    'Status' => ucfirst($payslip->status),
                    'Created Date' => $payslip->created_at?->format('Y-m-d'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Employee No',
            'Employee',
            'Year',
            'Month',
            'Period',
            'Basic Salary',
            'Allowances',
            'Deductions',
            'Tax',
            'Net Pay',
            'Status',
            'Created Date',
        ];
    }
}