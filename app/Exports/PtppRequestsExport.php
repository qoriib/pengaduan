<?php

namespace App\Exports;

use App\Models\PtppRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PtppRequestsExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return PtppRequest::with(['fromUser', 'toUser', 'requestDetail', 'approvals'])
            ->where('status', 'completed')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No Form',
            'Tanggal Permintaan',
            'Pemohon',
            'Fungsi Pemohon',
            'Tujuan',
            'Fungsi Tujuan',
            'Area Temuan',
            'Kategori',
            'Sumber Ketidaksesuaian',
            'Deskripsi Ketidaksesuaian',
            'Batas Waktu Jawab',
            'Status',
            'Analisa Penyebab',
            'Tindakan Perbaikan/Pencegahan',
            'PIC',
            'Waktu Eksekusi',
            'Dokumen Direvisi',
            'Target Verifikasi',
            'Status Verifikasi Terakhir',
            'Tanggal Approve Terakhir'
        ];
    }

    public function map($request): array
    {
        $lastApproval = $request->approvals->last();

        return [
            $request->no_form,
            optional($request->request_date)->format('d-m-Y'),
            optional($request->fromUser)->name,
            optional($request->fromUser)->role,
            optional($request->toUser)->name,
            optional($request->toUser)->role,
            $request->area_location,
            $request->category,
            is_array($request->source_of_nonconformity)
                ? implode(', ', $request->source_of_nonconformity)
                : $request->source_of_nonconformity,
            $request->nonconformity_description,
            optional($request->due_date)->format('d-m-Y'),
            $request->status,
            optional($request->requestDetail)->cause_analysis,
            optional($request->requestDetail)->correction_action,
            optional($request->requestDetail)->pic,
            optional($request->requestDetail->execution_time)->format('d-m-Y'),
            optional($request->requestDetail)->document_revised,
            optional($request->requestDetail->target_verification_date)->format('d-m-Y'),
            optional($lastApproval)->verification_status,
            optional($lastApproval->approved_at)->format('d-m-Y'),
        ];
    }
}
