@extends('layouts.app')

@section('title', 'Laporan PTPP')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Laporan PTPP</h2>
        <a href="{{ route('ptpp-requests.report.export') }}" class="btn btn-success">
            Export ke Excel
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>No Form</th>
                    <th>Tanggal</th>
                    <th>Dari</th>
                    <th>Ke</th>
                    <th>Area</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $i => $request)
                    <tr>
                        <td>{{ $i + $requests->firstItem() }}</td>
                        <td class="font-monospace">{{ $request->no_form }}</td>
                        <td class="font-monospace">{{ $request->request_date?->format('d-m-Y') }}</td>
                        <td>{{ $request->fromUser->name ?? '-' }}</td>
                        <td>{{ $request->toUser->name ?? '-' }}</td>
                        <td>{{ $request->area_location }}</td>
                        <td>{{ $request->category }}</td>
                        <td>
                            <a href="{{ route('ptpp-requests.detail.show', $request->id) }}" class="btn btn-sm btn-outline-info">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
{{ $requests->links() }}
@endsection