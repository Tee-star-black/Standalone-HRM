<?php

namespace App\Exports;

use App\Models\JobApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RecruitmentApplicationsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return JobApplication::with(['candidate.documents', 'vacancy'])
            ->latest()
            ->get()
            ->map(function ($application) {
                return [
                    'Candidate' => $application->candidate->full_name,
                    'Email' => $application->candidate->email,
                    'Phone' => $application->candidate->phone,
                    'Vacancy' => $application->vacancy->title,
                    'Stage' => ucfirst($application->stage),
                    'Status' => ucfirst($application->status),
                    'Score' => $application->score,
                    'CV Uploaded' => $application->candidate->documents->count() ? 'Yes' : 'No',
                    'Converted To Employee' => $application->converted_employee_id ? 'Yes' : 'No',
                    'Application Date' => $application->created_at?->format('Y-m-d'),
                    'Converted Date' => $application->converted_at?->format('Y-m-d'),
                    'Notes' => $application->notes,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Candidate',
            'Email',
            'Phone',
            'Vacancy',
            'Stage',
            'Status',
            'Score',
            'CV Uploaded',
            'Converted To Employee',
            'Application Date',
            'Converted Date',
            'Notes',
        ];
    }
}