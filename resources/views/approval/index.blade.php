@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Review Request (ITM)</h2>

    {{-- Persetujuan Awal --}}
    <div class="mb-5">
        <h4 class="text-primary">Persetujuan Awal ITM</h4>

        @forelse($initialRequests as $request)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $request->no_form }}</h5>
                    <p><strong>Deskripsi:</strong> {{ $request->nonconformity_description }}</p>
                    <p><strong>Area:</strong> {{ $request->area_location }}</p>
                    <p><strong>Dari:</strong> {{ $request->fromUser->name }}</p>
                    <p><strong>Untuk:</strong> {{ $request->toUser->name }}</p>

                    <form action="{{ route('approval.itm-initial-review-approve.handle', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>

                    <form action="{{ route('approval.itm-initial-review-reject.handle', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-muted">Tidak ada request yang menunggu persetujuan awal.</p>
        @endforelse
    </div>

    {{-- Persetujuan Akhir --}}
    <div>
        <h4 class="text-success">Persetujuan Akhir ITM</h4>

        @forelse($finalRequests as $request)
            <div class="card mb-3 border-success">
                <div class="card-body">
                    <h5>{{ $request->no_form }}</h5>
                    <p><strong>Deskripsi:</strong> {{ $request->nonconformity_description }}</p>
                    <p><strong>Area:</strong> {{ $request->area_location }}</p>
                    <p><strong>Dari:</strong> {{ $request->fromUser->name }}</p>
                    <p><strong>Untuk:</strong> {{ $request->toUser->name }}</p>

                    <form action="{{ route('approval.itm-final-review-approve.handle', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">Final Approve</button>
                    </form>

                    <form action="{{ route('approval.itm-final-review-reject.handle', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-muted">Tidak ada request yang menunggu persetujuan akhir.</p>
        @endforelse
    </div>
</div>

@endsection
