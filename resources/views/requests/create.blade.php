@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Buat Request Baru</h2>

    <form action="{{ route('requests.create.handle') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="to_user_id" class="form-label">Tujuan User</label>
            <select name="to_user_id" class="form-control" required>
                <option value="">-- Pilih User Tujuan --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Area / Lokasi Temuan</label>
            <input type="text" name="area_location" class="form-control">
        </div>

        <div class="mb-3">
            <label>Sumber Ketidaksesuaian</label>
            <select name="source_of_nonconformity[]" multiple class="form-control">
                <option value="Keluhan">Keluhan</option>
                <option value="Audit">Audit</option>
                <option value="Inspeksi">Inspeksi</option>
                <option value="Observasi">Observasi</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Deskripsi Ketidaksesuaian</label>
            <textarea name="nonconformity_description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Persyaratan yang Dilanggar</label>
            <textarea name="requirement_violated" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="category" class="form-control">
                <option value="">-- Pilih Kategori --</option>
                <option value="Temuan">Temuan</option>
                <option value="Observasi">Observasi</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Batas Waktu Jawab</label>
            <input type="date" name="due_date" class="form-control">
        </div>

        <div class="mb-3">
            <label>Upload Foto Ilustrasi</label>
            <input type="file" name="illustration_photo" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>
</div>
@endsection
