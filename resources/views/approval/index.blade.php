@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Persetujuan Permohonan (ITM)</h2>

    <div class="vstack gap-4">
        <div class="card">
            <div class="card-header">
                <strong>Persetujuan Awal</strong>
            </div>
            <div class="card-body">
                @if($initialRequests->isEmpty())
                    <p class="text-muted text-center mb-0">Tidak ada permohonan yang menunggu persetujuan awal.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No Form</th>
                                    <th>Deskripsi</th>
                                    <th>Area</th>
                                    <th>Dari</th>
                                    <th>Untuk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($initialRequests as $request)
                                    <tr>
                                        <td class="font-monospace">{{ $request->no_form }}</td>
                                        <td>{{ $request->nonconformity_description }}</td>
                                        <td>{{ $request->area_location }}</td>
                                        <td>{{ $request->fromUser->name }}</td>
                                        <td>{{ $request->toUser->name }}</td>
                                        <td class="text-nowrap">
                                            <form action="{{ route('approval.itm-initial-review-approve.handle', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('approval.itm-initial-review-reject.handle', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <strong>Persetujuan Akhir</strong>
            </div>
            <div class="card-body">
                @if($finalRequests->isEmpty())
                    <p class="text-muted text-center mb-0">Tidak ada permohonan yang menunggu persetujuan akhir.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No Form</th>
                                    <th>Deskripsi</th>
                                    <th>Area</th>
                                    <th>Dari</th>
                                    <th>Untuk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($finalRequests as $request)
                                    <tr>
                                        <td class="font-monospace">{{ $request->no_form }}</td>
                                        <td>{{ $request->nonconformity_description }}</td>
                                        <td>{{ $request->area_location }}</td>
                                        <td>{{ $request->fromUser->name }}</td>
                                        <td>{{ $request->toUser->name }}</td>
                                        <td class="text-nowrap">
                                            <form action="{{ route('approval.itm-final-review-approve.handle', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm">Final Approve</button>
                                            </form>
                                            <form action="{{ route('approval.itm-final-review-reject.handle', $request->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection