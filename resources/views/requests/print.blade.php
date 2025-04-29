@extends('layouts.print')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
    }

    .form-ptpp {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    .form-ptpp table {
        width: 100%;
        border-collapse: collapse;
    }

    .form-ptpp td, .form-ptpp th {
        border: 1px solid #000;
        padding: 4px;
        vertical-align: top;
    }

    .header-center {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
    }

    .section-hint {
        background: #d6d6f0;
        font-size: 10px;
        font-style: italic;
        padding: 4px;
    }

    .section-title {
        font-weight: bold;
        text-align: center;
        padding: 4px;
    }

    .no-border td {
        border: none !important;
    }

    .checkbox {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 1px solid #000;
        margin-right: 5px;
    }
</style>

<div class="form-ptpp">
    <table>
        <tr>
            <td colspan="2">No. : {{ $request->no_form }}</td>
            <td colspan="3" rowspan="2" class="header-center">
                PERMINTAAN TINDAKAN PERBAIKAN DAN PENCEGAHAN (PTPP)
            </td>
        </tr>
        <tr>
            <td colspan="2">Tgl : {{ $request->request_date?->format('d / m / Y') }}</td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td colspan="3">Kepada / Fungsi: {{ $request->toUser->name ?? '' }} / {{ $request->toUser->role }}</td>
            <td colspan="2" rowspan="2">Area / Lokasi Temuan: <br>{{ $request->area_location }}</td>
        </tr>
        <tr>
            <td colspan="4">Dari / Fungsi: {{ $request->fromUser->name ?? '' }} / {{ $request->fromUser->role }}</td>
        </tr>
        <tr>
            <td colspan="5" class="section-hint">Diisi oleh pemohon / auditor</td>
        </tr>
        <tr>
            <td colspan="5" class="section-title">SUMBER KETIDAKSESUAIAN ATAU POTENSINYA</td>
        </tr>
        @php
            $sources = is_array($request->source) ? $request->source : explode(',', $request->source);
        @endphp
        <tr>
            <td colspan="5">
                {{ $request->source_of_nonconformity 
                    ? implode(', ', json_decode($request->source_of_nonconformity)) 
                    : '-' 
                }}
            </td>
        </tr>
        <tr>
            <td colspan="5" class="section-title">KETIDAKSESUAIAN ATAU POTENSI YANG DITEMUKAN</td>
        </tr>
        <tr>
            <td colspan="4">{{ $request->nonconformity_description }}</td>
            <td colspan="1">
                Batas waktu jawab: <br>{{ $request->due_date?->format('d / m / Y') ?? '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="4">Persyaratan yang dilanggar: {{ $request->requirement_violated ?? '-' }}</td>
            <td colspan="1">Kategori: {{ $request->category }}</td>
        </tr>
        <tr>
            <td colspan="3" style="height:100px">ILUSTRASI / GAMBAR:<br>
                @if($request->illustration_photo_path)
                    <img src="{{ asset('storage/' . $request->illustration_photo_path) }}" style="max-height: 90px;">
                @endif
            </td>
            @php
                $approvalInitial = $request->approvals()->where('stage', 'itm_initial_review')->first();
            @endphp
            <td>
                <span class="text-nowrap">Pemohon / Auditor</span><br>
                @if ($approvalInitial)
                    <div id="qrcode-requester-initial"></div>
                    <script>
                        new QRCode(document.getElementById("qrcode-requester-initial"), {
                            text: "{{ $approvalInitial->qr_code_content }}",
                            width: 60,
                            height: 60
                        });
                    </script>
                    {{ $request->fromUser()->first()->name }} <br>
                    Tgl: {{ $request->request_date?->format('d / m / Y') }}
                @endif
            </td>
            <td>
                <span class="text-nowrap">Disetujui oleh,</span><br>
                @if ($approvalInitial)
                    <div id="qrcode-itm-initial"></div>
                    <script>
                        new QRCode(document.getElementById("qrcode-itm-initial"), {
                            text: "{{ $approvalInitial->qr_code_content }}",
                            width: 60,
                            height: 60
                        });
                    </script>
                    {{ $approvalInitial->approverUser()->first()->name }} <br>
                    Tgl: {{ $approvalInitial->approved_at?->format('d / m / Y') }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="5" class="section-hint">Diisi oleh penerima CAR / PAR</td>
        </tr>
        <tr>
            <td colspan="5" class="section-title">TINDAK LANJUT</td>
        </tr>
        <tr>
            <td colspan="2">Perbaikan / Tindakan Sementara: <br>{{ $request->requestDetail->temporary_repair ?? '-' }}</td>
            <td>
                Tgl Terima CAR PAR: <br>{{ $request->requestDetail->received_at?->format('d / m / Y') ?? '-' }}<br>
            </td>
            @php
                $approvalResolver = $request->approvals()->where('stage', 'executor_response')->first();
                $approvalFinal = $request->approvals()->where('stage', 'itm_final_review')->first();
                $requestDetail = $request->requestDetail()->first();
                $resolver = $requestDetail->resolver()->first();
            @endphp
            <td>
                <span class="text-nowrap">Penanggung Jawab</span><br>
                @if ($approvalResolver)
                    <div id="qrcode-resolver-execution"></div>
                    <script>
                        new QRCode(document.getElementById("qrcode-resolver-execution"), {
                            text: "{{ $approvalResolver->qr_code_content }}",
                            width: 60,
                            height: 60
                        });
                    </script>
                    {{ $approvalResolver->approverUser()->first()->name }} <br>
                @endif
            </td>
            <td>
                <span class="text-nowrap">Disetujui oleh,</span><br>
                @if ($approvalFinal)
                    <div id="qrcode-itm-final"></div>
                    <script>
                        new QRCode(document.getElementById("qrcode-itm-final"), {
                            text: "{{ $approvalFinal->qr_code_content }}",
                            width: 60,
                            height: 60
                        });
                    </script>
                    {{ $approvalFinal->approverUser()->first()->name }} <br>
                @endif
            </td>
        </tr>
        <tr class="text-center">
            <th>No</th>
            <th>Analisa Penyebab</th>
            <th>Tindakan Perbaikan dan Pencegahan</th>
            <th>PIC</th>
            <th>Waktu Pelaksanaan</th>
        </tr>
        <tr>
            <td>1</td>
            <td>{{ $request->requestDetail->cause_analysis ?? '-' }}</td>
            <td>{{ $request->requestDetail->correction_action ?? '-' }}</td>
            <td>{{ $request->requestDetail->pic ?? '-' }}</td>
            <td>{{ $request->requestDetail->execution_time?->format('d / m / Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="3">Dokumen yang direvisi (jika ada): <br>{{ $request->requestDetail->document_revised ?? '-' }}</td>
            <td colspan="2">Target Waktu Verifikasi: <br>{{ $request->requestDetail->target_verification_date?->format('d / m / Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="5" class="section-hint">Diisi oleh pemohon / auditor</td>
        </tr>
        <tr>
            <td colspan="5" class="section-title">VERIFIKASI PELAKSANAAN TINDAKAN PERBAIKAN DAN PENCEGAHAN</td>
        </tr>
        <tr>
            <td colspan="4">
                Status:<br>
                [ {{ optional($request->approvals->last())->verification_status === 'Close' ? '✔' : 'x' }} ] Close<br>
                [ {{ optional($request->approvals->last())->verification_status === 'Follow Up' ? '✔' : 'x' }} ] Perlu Follow Up<br>
                Target verifikasi selanjutnya: {{ optional($request->approvals->last())->next_verification_target?->format('d / m / Y') ?? '-' }}
            </td>
            @php
                $approvalRequester = $request->approvals()->where('stage', 'requester_review')->first();
            @endphp
            <td colspan="1">
                <span class="text-nowrap">Approval Pemohon / Auditor:</span><br>
                @if ($approvalRequester)
                    <div id="qrcode-requester-review"></div>
                    <script>
                        new QRCode(document.getElementById("qrcode-requester-review"), {
                            text: "{{ $approvalRequester->qr_code_content }}",
                            width: 60,
                            height: 60
                        });
                    </script>
                    {{ $request->fromUser()->first()->name ?? '-' }}
                @endif
            </td>
        </tr>
    </table>
    <p style="font-size:10px;"><i>Catatan: Jika tindakan perbaikan/pencegahan belum memenuhi maka terbitkan PTPP baru</i></p>
</div>

<div class="no-print mt-4">
    <a href="{{ route('requests.detail.show', $request->id) }}" class="btn btn-secondary">Kembali</a>
    <button onclick="window.print()" class="btn btn-primary">Print</button>
</div>
@endsection
