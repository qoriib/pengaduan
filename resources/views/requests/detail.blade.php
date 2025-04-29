@extends('layouts.app')

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
                                <th class="table-light" style="width: 25%">Tanggal Permintaan</th>
                                <td class="font-monospace">{{ $request->request_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Dari</th>
                                <td>{{ $request->fromUser->name }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Kepada</th>
                                <td>{{ $request->toUser->name }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Area / Lokasi Temuan</th>
                                <td>{{ $request->area_location }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Kategori</th>
                                <td>{{ $request->category }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Batas Waktu Jawab</th>
                                <td class="font-monospace">{{ $request->due_date?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Status</th>
                                <td>
                                    @switch($request->status)
                                        @case('waiting_itm_initial_review')
                                            <span class="badge bg-warning text-dark">Menunggu Persetujuan Awal ITM</span>
                                            @break
                                        @case('waiting_executor')
                                            <span class="badge bg-primary">Menunggu Pelaksana</span>
                                            @break
                                        @case('waiting_requester_review')
                                            <span class="badge bg-success">Sudah Ditintaklanjuti</span>
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
                            <tr>
                                <th class="table-light">Deskripsi Ketidaksesuaian</th>
                                <td>{{ $request->nonconformity_description }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Persyaratan yang Dilanggar</th>
                                <td>{{ $request->requirement_violated ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="table-light">Sumber Ketidaksesuaian</th>
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
                            @if($request->illustration_photo_path)
                            <tr>
                                <th>Ilustrasi</th>
                                <td><img src="{{ asset('storage/' . $request->illustration_photo_path) }}" alt="Ilustrasi" class="img-thumbnail" style="max-width: 300px;"></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if($request->requestDetail)
            <div class="card">
                <div class="card-header">Rincian Tindakan Perbaikan</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th class="table-light">Diterima pada</th>
                                    <td class="font-monospace">{{ $request->requestDetail->received_at?->format('d/m/Y') ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Perbaikan Sementara</th>
                                    <td>{{ $request->requestDetail->temporary_repair ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Analisa Penyebab</th>
                                    <td>{{ $request->requestDetail->cause_analysis }}</td>
                                </tr>
                                <tr>
                                    <th class="table-light">Tindakan Perbaikan & Pencegahan</th>
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
                                    <th class="table-light">Target Verifikasi</th>
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
                <table class="table table-bordered mb-0">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Tahap</th>
                            <th>Disetujui oleh</th>
                            <th>Tanggal & Waktu</th>
                            <th>Status Verifikasi</th>
                            <th>Verifikasi Ulang</th>
                            <th>QR Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($request->approvals as $approval)
                            <tr>
                                <td>
                                    @switch($approval->stage)
                                        @case('itm_initial_review')
                                            <span class="badge bg-success">Persetujuan Awal ITM</span>
                                            @break
                                        @case('executor_response')
                                            <span class="badge bg-success">Ditinjaklanjuti</span>
                                            @break
                                        @case('requester_review')
                                            <span class="badge bg-success">Disetuji Pemohon</span>
                                            @break
                                        @case('itm_final_review')
                                            <span class="badge bg-success">Persetujuan Akhir ITM</span>
                                            @break
                                        @default
                                            <span class="badge bg-success">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $approval->approverUser->name }}</td>
                                <td>{{ $approval->approved_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td>{{ $approval->verification_status ?? '-' }}</td>
                                <td>
                                    @if($approval->verification_status === 'Follow Up')
                                        {{ $approval->next_verification_target?->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($approval->qr_code_path)
                                        <img src="{{ asset('storage/' . $approval->qr_code_path) }}" alt="QR" width="60">
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada approval</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="{{ route('requests.show') }}" class="btn btn-secondary">Kembali</a>
@endsection