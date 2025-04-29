@extends('layouts.app')

@section('title', 'Detail Permintaan')

@section('content')
    <h2 class="mb-4">Detail Permintaan - <span class="font-monospace">{{ $request->no_form }}</span></h2>
    <div class="vstack gap-4 mb-4">
        <div class="card">
            <div class="card-header">Informasi Permintaan</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th class="table-light" style="width: 25%">Tanggal Permohonan</th>
                                <td class="font-monospace">{{ $request->request_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Dari / Fungsi</th>
                                <td>{{ $request->fromUser->name }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Kepada / Fungsi</th>
                                <td>{{ $request->toUser->name }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Area / Lokasi Temuan</th>
                                <td>{{ $request->area_location }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Sumber Ketidaksesuaian atau Potensinya</th>
                                <td>
                                    @if ($request->source_of_nonconformity)
                                        <ul class="mb-0">
                                            @foreach(json_decode($request->source_of_nonconformity) as $source)
                                                <li>{{ $source }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="table-light">Ketidaksesuaian atau Potensi Yang Ditemukan</th>
                                <td>{{ $request->nonconformity_description }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Kategori</th>
                                <td>{{ $request->category }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Persyaratan yang Dilanggar</th>
                                <td>{{ $request->requirement_violated ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Batas Waktu Jawab</th>
                                <td class="font-monospace">{{ $request->due_date?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                            @if($request->illustration_photo_path)
                                <tr>
                                    <th>Ilustrasi</th>
                                    <td><img src="{{ asset('storage/' . $request->illustration_photo_path) }}" alt="Ilustrasi" class="img-thumbnail" style="max-width: 300px;"></td>
                                </tr>
                            @endif
                            <tr>
                                <th class="table-light">Status</th>
                                <td>
                                    @switch($request->status)
                                        @case('waiting_itm_initial_review')
                                            <span class="badge bg-warning text-dark">Menunggu Persetujuan Awal ITM</span>
                                            @break
                                        @case('waiting_executor')
                                            <span class="badge bg-primary">Menunggu Tindakan</span>
                                            @break
                                        @case('waiting_requester_review')
                                            <span class="badge bg-success">Sudah Ditindak</span>
                                            <span class="badge bg-warning text-dark">Menunggu Persetujuan Pemohon</span>
                                            @break
                                        @case('waiting_itm_final_review')
                                            <span class="badge bg-warning text-dark">Menunggu Persetujuan Akhir ITM</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-success">Selesai</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span>
                                    @endswitch
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if($request->requestDetail)
            <div class="card">
                <div class="card-header">Rincian Perbaikan / Tindakan</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th class="table-light">Diterima pada</th>
                                    <td class="font-monospace">{{ $request->requestDetail->received_at?->format('d/m/Y') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Perbaikan / Tindakan Sementara</th>
                                    <td>{{ $request->requestDetail->temporary_repair ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Analisa Penyebab</th>
                                    <td>{{ $request->requestDetail->cause_analysis }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Tindakan Perbaikan dan Pencegahan</th>
                                    <td>{{ $request->requestDetail->correction_action }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">PIC</th>
                                    <td>{{ $request->requestDetail->pic }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Waktu Pelaksanaan</th>
                                    <td class="font-monospace">{{ $request->requestDetail->execution_time?->format('d/m/Y') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Dokumen Direvisi</th>
                                    <td>{{ $request->requestDetail->document_revised ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Target Waktu Verifikasi</th>
                                    <td class="font-monospace">{{ $request->requestDetail->target_verification_date?->format('d/m/Y') ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-header">Riwayat Approval</div>
            <div class="card-body">
                @forelse($request->approvals as $approval)
                    <div class="border rounded p-3 mb-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            @switch($approval->stage)
                                @case('itm_initial_review')
                                    <span class="badge bg-success">Persetujuan Awal ITM</span>
                                    @break
                                @case('executor_response')
                                    <span class="badge bg-success">Ditinjaklanjuti</span>
                                    @break
                                @case('requester_review')
                                    <span class="badge bg-success">Disetujui Pemohon</span>
                                    @break
                                @case('itm_final_review')
                                    <span class="badge bg-success">Persetujuan Akhir ITM</span>
                                    @break
                                @default
                                    <span class="badge bg-success">{{ ucfirst(str_replace('_', ' ', $approval->stage)) }}</span>
                            @endswitch
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Disetujui oleh:</strong> {{ $approval->approverUser->name }}
                            </div>
                            <div class="col-md-6">
                                <strong>Tanggal & Waktu:</strong> <span class="font-monospace">{{ $approval->approved_at?->format('d/m/Y') ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Status Verifikasi:</strong> {{ $approval->verification_status ?? '-' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Verifikasi Ulang:</strong>
                                @if($approval->verification_status === 'Follow Up')
                                    {{ $approval->next_verification_target?->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div>
                            @if($approval->qr_code_content)
                                <div id="qrcode-{{ $loop->index }}" class="mt-2"></div>
                                <script>
                                    new QRCode(document.getElementById("qrcode-{{ $loop->index }}"), {
                                        text: "{{ $approval->qr_code_content }}",
                                        width: 120,
                                        height: 120
                                    });
                                </script>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted">Belum ada approval</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="hstack gap-2">
        <a href="{{ route('requests.show') }}" class="btn btn-secondary">Kembali</a>
        <a href="{{ route('requests.print', $request->id) }}" class="btn btn-info">Print</a>
    </div>
@endsection
