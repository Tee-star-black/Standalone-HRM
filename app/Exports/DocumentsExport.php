<?php

namespace App\Exports;

use App\Models\Document;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Document::latest()
            ->get()
            ->map(function ($document) {
                return [
                    'Document Name' => $document->original_name,
                    'Owner Type' => class_basename($document->documentable_type),
                    'Owner ID' => $document->documentable_id,
                    'Disk' => $document->disk,
                    'File Path' => $document->path,
                    'MIME Type' => $document->mime_type,
                    'Size KB' => $document->size ? round($document->size / 1024, 2) : 0,
                    'Uploaded Date' => $document->created_at?->format('Y-m-d H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Document Name',
            'Owner Type',
            'Owner ID',
            'Disk',
            'File Path',
            'MIME Type',
            'Size KB',
            'Uploaded Date',
        ];
    }
}