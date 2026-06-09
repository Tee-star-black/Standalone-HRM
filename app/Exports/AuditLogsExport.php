<?php

namespace App\Exports;

use App\Models\AuditLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AuditLogsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return AuditLog::with('user')
            ->latest()
            ->get()
            ->map(function ($log) {
                return [
                    'Date' => $log->created_at?->format('Y-m-d H:i:s'),
                    'User' => $log->user?->name ?? 'System',
                    'Email' => $log->user?->email ?? '-',
                    'Action' => $log->action,
                    'Description' => $log->description,
                    'Model Type' => $log->model_type ? class_basename($log->model_type) : '-',
                    'Model ID' => $log->model_id ?? '-',
                    'IP Address' => $log->ip_address ?? '-',
                    'User Agent' => $log->user_agent ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Date',
            'User',
            'Email',
            'Action',
            'Description',
            'Model Type',
            'Model ID',
            'IP Address',
            'User Agent',
        ];
    }
}