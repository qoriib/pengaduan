@extends('layouts.app')

@section('title', 'Daftar Request')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Request</h2>
        <a href="{{ route('requests.create.show') }}" class="btn btn-primary">
            + Ajukan Request Baru
        </a>
    </div>

    {{-- Request yang saya ajukan --}}
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white">
            <strong>Request yang Saya Ajukan</strong>
        </div>
        <div class="card-body">
            @if ($myRequests->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
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
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $request->no_form }}</td>
                                    <td>{{ $request->toUser->name ?? '-' }}</td>
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
                                                <span class="badge bg-warning text-dark">Menunggu Persetujuan Pengaju</span>
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
                                    <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($request->status === 'waiting_requester_review')
                                        <form action="{{ route('approval.requester-review-approve.handle', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form action="{{ route('approval.requester-review-reject.handle', $request->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    @endif
                                        <a href="{{ url('/requests/' . $request->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">Belum ada request yang Anda ajukan.</p>
            @endif
        </div>
    </div>

    {{-- Request yang ditujukan ke saya --}}
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <strong>Request Masuk untuk Saya</strong>
        </div>
        <div class="card-body">
            @if ($requestsToMe->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>No Form</th>
                                <th>Pengirim</th>
                                <th>Status</th>
                                <th>Tanggal Masuk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requestsToMe as $request)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $request->no_form }}</td>
                                    <td>{{ $request->fromUser->name ?? '-' }}</td>
                                    <td>
                                        @switch($request->status)
                                            @case('waiting_itm_initial_review')
                                                <span class="badge bg-warning text-dark">Menunggu Persetujuan Awal ITM</span>
                                                @break
                                            @case('waiting_executor')
                                                <span class="badge bg-primary">Perlu Tindak Lanjut</span>
                                                @break
                                            @case('waiting_requester_review')
                                                <span class="badge bg-success">Sudah Ditindaklanjuti</span>
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
                                    <td>{{ $request->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($request->status === 'waiting_executor' && $request->to_user_id === Auth::id())
                                            <a href="{{ route('request-details.create.show', $request->id) }}" class="btn btn-sm btn-primary">
                                                Tindak Lanjuti
                                            </a>
                                        @endif
                                        <a href="{{ url('/requests/' . $request->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">Tidak ada request masuk untuk Anda.</p>
            @endif
        </div>
    </div>
@endsection