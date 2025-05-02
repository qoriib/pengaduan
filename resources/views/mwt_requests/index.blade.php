@extends('layouts.app')

@section('title', 'Daftar Laporan MWT')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Daftar Laporan MWT</h2>
        <div class="hstack gap-2">
            <a href="{{ route('mwt-requests.create.show') }}" class="btn btn-success">
                Ajukan
            </a>
            @if (auth()->user()?->role === 'ITM')
                <a href="{{ route('mwt-requests.export') }}" class="btn btn-info">
                    Export
                </a>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th>Tanggal</th>
                    <th>Pelaksana</th>
                    <th>Diketahui oleh</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody >
                @forelse($reports as $report)
                    <tr>
                        <td class="text-center font-monospace">{{ $report->execution_date->format('d/m/Y') }}</td>
                        <td>
                            <ul>
                                @foreach ($report->participants as $participant)
                                    <li>{{ $participant->user->name }} ({{ $participant->user->role }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            {{ $report->acknowledger?->name ?? '-' }}
                        </td>
                        <td class="text-center">
                            @if($report->approved_by_qr_code_content)
                                <span class="badge bg-success">Disetujui</span>
                            @else
                                <span class="badge bg-info">Menunggu Persetujuan</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-outline-info btn-sm" href="{{ route('mwt-requests.detail.show', $report->id) }}">
                                Lihat Detail
                            </a>
                        
                            @if (auth()->user()?->role === 'ITM' && !$report->approved_by_qr_code_content)
                                <form action="{{ route('mwt-requests.approve.handle', $report->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            Belum ada laporan MWT.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection