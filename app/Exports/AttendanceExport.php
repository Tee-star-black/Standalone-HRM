<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Attendance::with('employee')
            ->latest('date')
            ->get()
            ->map(function ($attendance) {
                return [
                    'Employee No' => $attendance->employee->employee_number ?? '-',
                    'Employee' => $attendance->employee->full_name ?? '-',
                    'Date' => $attendance->date?->format('Y-m-d'),
                    'Clock In' => $attendance->clock_in?->format('H:i'),
                    'Clock Out' => $attendance->clock_out?->format('H:i'),
                    'Hours Worked' => $attendance->total_minutes
                        ? round($attendance->total_minutes / 60, 2)
                        : 0,
                    'Status' => ucfirst($attendance->status),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Employee No',
            'Employee',
            'Date',
            'Clock In',
            'Clock Out',
            'Hours Worked',
            'Status',
        ];
    }
}