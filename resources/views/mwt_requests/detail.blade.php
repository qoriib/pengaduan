@extends('layouts.app')

@section('title', 'Detail Laporan MWT')

@section('content')
    <h2 class="mb-4">Detail Laporan MWT</h2>

    <div class="vstack gap-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Tanggal Pelaksanaan</h6>
                <p class="card-text">{{ $report->execution_date->format('d/m/Y') }}</p>
                <h6 class="card-title">Pelaksana MWT</h6>
                <ul class="card-text">
                    @foreach ($report->participants as $participant)
                        <li>{{ $participant->user->name }} ({{ $participant->user->role }})</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Hasil Dialog dengan Pekerja</h6>
                <ol class="card-text">
                    @foreach ($report->dialogues ?? [] as $dialogue)
                        <li>{{ $dialogue }}</li>
                    @endforeach
                </ol>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Temuan Positif</h6>
                <ol class="card-text">
                    @foreach ($report->positive_findings ?? [] as $positive)
                        <li>{{ $positive }}</li>
                    @endforeach
                </ol>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Kondisi Tidak Standar</h6>
                <ol class="card-text">
                    @foreach ($report->unsafe_conditions ?? [] as $unsafe)
                        <li>{{ $unsafe }}</li>
                    @endforeach
                </ol>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Dokumentasi</h6>
                @if ($report->documentation_path)
                    <img src="{{ asset('storage/' . $report->documentation_path) }}" class="img-fluid rounded" alt="Dokumentasi MWT">
                @else
                    <p class="card-text">Tidak ada dokumentasi.</p>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Tanda Tangan</h6>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Diketahui oleh</h6>
                        @if ($report->acknowledged_by_qr_code_content)
                            <div id="qrcode-acknowledged"></div>
                            <p class="mb-0 mt-2">{{ $report->acknowledger?->name ?? '-' }}</p>
                            <script>
                                new QRCode(document.getElementById("qrcode-acknowledged"), {
                                    text: "{{ $report->acknowledged_by_qr_code_content }}",
                                    width: 80,
                                    height: 80
                                });
                            </script>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6>Disetujui oleh</h6>
                        @if ($report->approved_by_qr_code_content)
                            <div id="qrcode-approved"></div>
                            <p class="mb-0 mt-2">{{ $report->approver?->name ?? '-' }}</p>
                            <script>
                                new QRCode(document.getElementById("qrcode-approved"), {
                                    text: {!! json_encode($report->approved_by_qr_code_content) !!},
                                    width: 80,
                                    height: 80
                                });
                            </script>
                        @elseif (auth()->user()?->role === 'ITM')
                            <form action="{{ route('mwt-requests.approve.handle', $report->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success mt-2">Setujui</button>
                            </form>
                        @else
                            <p class="text-muted">Belum disetujui</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hstack gap-2 mt-4">
        <a href="{{ route('mwt-requests.show') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('mwt-requests.print', $report) }}" class="btn btn-info">Print</a>
    </div>
@endsection