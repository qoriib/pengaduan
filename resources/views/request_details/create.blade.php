@extends('layouts.app')

@section('title', 'Rincian Tindakan')

@section('content')
    <h2 class="mb-4">Rincian Tindakan</h2>

    <form action="{{ route('request-details.create.handle', $request->id) }}" method="POST" class="vstack gap-3">
        @csrf
        <div>
            <label class="form-label">Tanggal Terima</label>
            <input type="date" name="received_at" class="form-control" required>
        </div>
        <div>
            <label class="form-label">Perbaikan / Tindakan Sementara</label>
            <textarea name="temporary_repair" class="form-control"></textarea>
        </div>
        <div>
            <label class="form-label">Analisa Penyebab</label>
            <textarea name="cause_analysis" class="form-control" required></textarea>
        </div>
        <div>
            <label class="form-label">Tindakan Perbaikan dan Pencegahan</label>
            <textarea name="correction_action" class="form-control" required></textarea>
        </div>
        <div>
            <label class="form-label">PIC</label>
            <input type="text" name="pic" class="form-control" required>
        </div>
        <div>
            <label class="form-label">Waktu Pelaksanaan</label>
            <input type="date" name="execution_time" class="form-control" required>
        </div>
        <div>
            <label class="form-label">Dokumen yang Direvisi</label>
            <input type="text" name="document_revised" list="document-options" class="form-control">
            <datalist id="document-options">
                <option value="Pedoman/Manual">
                <option value="TKO">
                <option value="TKI">
                <option value="TKPA">
                <option value="Formulir">
            </datalist>
        </div>
        <div>
            <label class="form-label">Target Waktu Verifikasi</label>
            <input type="date" name="target_verification_date" class="form-control">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Kirim</button>
            <a href="{{ route('requests.show') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection