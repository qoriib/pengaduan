<?php

namespace App\Exports;

use App\Models\MwtReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MwtReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return MwtReport::with(['participants.user', 'acknowledger', 'approver'])->get();
    }

    public function map($report): array
    {
        return [
            $report->id,
            $report->execution_date->format('d/m/Y'),
            implode(', ', $report->participants->pluck('user.name')->toArray()),
            implode('; ', $report->dialogues ?? []),
            implode('; ', $report->positive_findings ?? []),
            implode('; ', $report->unsafe_conditions ?? []),
            $report->acknowledger->name ?? '-',
            $report->approver->name ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal Pelaksanaan',
            'Pelaksana',
            'Dialog Pekerja',
            'Temuan Positif',
            'Kondisi Tidak Standar',
            'Diketahui oleh',
            'Disetujui oleh',
        ];
    }
}
