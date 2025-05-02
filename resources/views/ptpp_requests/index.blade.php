@extends('layouts.app')

@section('title', 'Daftar Permohonan PTPP')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Permohonan PTPP</h2>
        <a href="{{ route('ptpp-requests.create.show') }}" class="btn btn-success">
            Ajukan
        </a>
    </div>
    <div class="vstack gap-4">
        <div class="card">
            <div class="card-header">
                <strong>Permohonan yang Saya Ajukan</strong>
            </div>
            <div class="card-body">
                @if ($myRequests->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>No Form</th>
                                    <th>Tujuan</th>
                                    <th>Status</th>
                                    <th>Tanggal Diajukan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($myRequests as $request)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="font-monospace">{{ $request->no_form }}</td>
                                        <td>{{ $request->toUser->name ?? '-' }}</td>
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
                                        <td class="font-monospace text-center">{{ $request->created_at->format('d/m/Y') }}</td>
                                        <td class="text-nowrap text-center">
                                            @if ($request->status === 'waiting_requester_review')
                                            <form action="{{ route('ptpp-approval.requester-review-approve.handle', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <form action="{{ route('ptpp-approval.requester-review-reject.handle', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                            </form>
                                        @endif
                                            <a href="{{ route('ptpp-requests.detail.show', $request->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">Belum ada permohonan yang Anda ajukan.</p>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <strong>Permohonan Masuk untuk Saya</strong>
            </div>
            <div class="card-body">
                @if ($requestsToMe->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>No Form</th>
                                    <th>Pengirim</th>
                                    <th>Status</th>
                                    <th>Tanggal Diajukan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requestsToMe as $request)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="font-monospace">{{ $request->no_form }}</td>
                                        <td>{{ $request->fromUser->name ?? '-' }}</td>
                                        <td>
                                            @switch($request->status)
                                                @case('waiting_itm_initial_review')
                                                    <span class="badge bg-warning text-dark">Menunggu Persetujuan Awal ITM</span>
                                                    @break
                                                @case('waiting_executor')
                                                    <span class="badge bg-primary">Perlu Tindakan</span>
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
                                        <td class="font-monospace text-center">{{ $request->created_at->format('d/m/Y') }}</td>
                                        <td class="text-nowrap text-center">
                                            @if ($request->status === 'waiting_executor' && $request->to_user_id === Auth::id())
                                                <a href="{{ route('ptpp-request-details.create.show', $request->id) }}" class="btn btn-sm btn-primary">
                                                    Tindak
                                                </a>
                                            @endif
                                            <a href="{{ route('ptpp-requests.detail.show', $request->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">Tidak ada permohonan masuk untuk Anda.</p>
                @endif
            </div>
        </div>
    </div>
@endsection