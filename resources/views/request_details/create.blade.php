@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Input Rincian Perbaikan</h3>

    <form action="{{ route('request-details.create.handle', $request->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tanggal Terima CAR PAR</label>
            <input type="date" name="received_at" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tindakan Sementara</label>
            <textarea name="temporary_repair" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Analisa Penyebab</label>
            <textarea name="cause_analysis" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Tindakan Perbaikan & Pencegahan</label>
            <textarea name="correction_action" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">PIC</label>
            <input type="text" name="pic" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Waktu Pelaksanaan</label>
            <input type="date" name="execution_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Dokumen Direvisi</label>
            <input type="text" name="document_revised" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Target Waktu Verifikasi</label>
            <input type="date" name="target_verification_date" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>
@endsection