@extends('layouts.print')

@section('title', 'Cetak Laporan MWT')

@section('content')
    <style>
        @media print {
            .no-print { display: none !important; }
        }
    </style>
    <table class="table table-bordered">
        <tr>
            <th colspan="3" class="text-center h4">LAPORAN MANAGEMENT WALKTHROUGH (MWT)</th>
        </tr>
        <tr class="text-center">
            <td colspan="2">Pelaksana MWT</td>
            <td>Tanda Tangan</td>
        </tr>
        @foreach ($report->participants as $i => $participant)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $participant->user->name }}</td>
                <td>
                    <div id="qr-par-{{ $i }}"></div>
                    <script>
                        new QRCode(document.getElementById("qr-par-{{ $i }}"), {
                            text: {!! json_encode($participant->qr_code_content) !!},
                            width: 80,
                            height: 80
                        });
                    </script>
                </td>
            </tr>
        @endforeach
        <tr class="text-center">
            <td colspan="2">Tanggal Pelaksanaan MWT</td>
            <td class="font-monospace">{{ $report->execution_date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th colspan="3">Hasil Dialog Management Dengan Pekerja Lapangan</th>
        </tr>
        @foreach ($report->dialogues ?? [] as $text)
            <tr>
                <td class="text-center">{{ $loop->index + 1 }}</td>
                <td colspan="2">{{ $text }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">Temuan Positif</th>
        </tr>
        @foreach ($report->positive_findings ?? [] as $text)
            <tr>
                <td class="text-center">{{ $loop->index + 1 }}</td>
                <td colspan="2">{{ $text }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">Kondisi Tidak Standar (Unsafe Condition</th>
        </tr>
        @foreach ($report->unsafe_conditions ?? [] as $text)
            <tr>
                <td class="text-center">{{ $loop->index + 1 }}</td>
                <td colspan="2">{{ $text }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">Dokumentasi</th>
        </tr>
        <tr>
            <td class="text-center" colspan="3">
                @if ($report->documentation_path)
                    <img src="{{ asset('storage/' . $report->documentation_path) }}" class="img-fluid my-3" style="max-height: 300px;">
                @else
                    <p class="mb-0">Tidak ada dokumentasi.</p>
                @endif
            </td>
        </tr>
    </table>

    <div class="row mt-4">
        <div class="col-6 vstack align-items-center text-center">
            <p><strong>Diketahui oleh,</strong></p>
            @if ($report->acknowledged_by_qr_code_content)
                <div id="qr-ack"></div>
                <p class="mt-2">{{ $report->acknowledger?->name ?? '-' }}</p>
                <script>
                    new QRCode(document.getElementById("qr-ack"), {
                        text: {!! json_encode($report->acknowledged_by_qr_code_content) !!},
                        width: 80,
                        height: 80
                    });
                </script>
            @endif
        </div>
        <div class="col-6 vstack align-items-center text-center">
            <p><strong>Disetujui oleh,</strong></p>
            @if ($report->approved_by_qr_code_content)
                <div id="qr-app"></div>
                <p class="mt-2">{{ $report->approver?->name ?? '-' }}</p>
                <script>
                    new QRCode(document.getElementById("qr-app"), {
                        text: {!! json_encode($report->approved_by_qr_code_content) !!},
                        width: 80,
                        height: 80
                    });
                </script>
            @else
                <p class="text-muted">Belum disetujui</p>
            @endif
        </div>
    </div>

    <div class="no-print mt-4">
        <a href="{{ route('mwt-requests.detail.show', $report->id) }}" class="btn btn-secondary">Kembali</a>
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
@endsection