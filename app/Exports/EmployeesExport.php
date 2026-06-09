<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Employee::latest()
            ->get()
            ->map(function ($employee) {
                return [
                    'Employee No' => $employee->employee_number,
                    'Full Name' => $employee->full_name,
                    'Email' => $employee->email,
                    'Phone' => $employee->phone,
                    'Job Title' => $employee->job_title,
                    'Employment Type' => $employee->employment_type,
                    'Status' => ucfirst($employee->status),
                    'Hire Date' => $employee->hire_date?->format('Y-m-d'),
                    'Created Date' => $employee->created_at?->format('Y-m-d'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Employee No',
            'Full Name',
            'Email',
            'Phone',
            'Job Title',
            'Employment Type',
            'Status',
            'Hire Date',
            'Created Date',
        ];
    }
}