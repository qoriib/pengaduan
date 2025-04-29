@extends('layouts.app')

@section('title', 'Permohonan Baru')

@section('content')
    <h2 class="mb-4">Permohonan Baru</h2>

    <form action="{{ route('requests.create.handle') }}" method="POST" enctype="multipart/form-data" class="vstack gap-3">
        @csrf
        <div>
            <label for="to_user_id" class="form-label">Kepada / Fungsi</label>
            <select name="to_user_id" class="form-select" required>
                <option value="">-- Pilih User Tujuan --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Area / Lokasi Temuan</label>
            <input type="text" name="area_location" class="form-control">
        </div>
        <div id="nonconformity-container">
            <label class="form-label">Sumber Ketidaksesuaian atau potensinya</label>
            <input type="text" name="source_of_nonconformity[]" list="nonconformity-options" class="form-control mb-2" />
            <button type="button" class="btn btn-primary" onclick="addNonconformityInput()">Tambah Sumber</button>
            <datalist id="nonconformity-options">
                <option value="Keluhan Pelanggan">
                <option value="Audit">
                <option value="Tinjauan Manajemen">
                <option value="Survei Pelanggan">
                <option value="Usulan/Saran">
            </datalist>
        </div>
        <div>
            <label class="form-label">Ketidaksesuaian atau Potensi Yang Ditemukan</label>
            <textarea name="nonconformity_description" class="form-control" required></textarea>
        </div>
        <div>
            <label class="form-label">Persyaratan Yang Dilanggar</label>
            <textarea name="requirement_violated" class="form-control"></textarea>
        </div>
        <div>
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select">
                <option value="">-- Pilih Kategori --</option>
                <option value="Temuan">Temuan</option>
                <option value="Observasi">Observasi</option>
            </select>
        </div>
        <div>
            <label class="form-label">Batas Waktu Jawab</label>
            <input type="date" name="due_date" class="form-control">
        </div>
        <div>
            <label class="form-label">Unggah Foto Ilustrasi</label>
            <input type="file" name="illustration_photo" class="form-control">
        </div>
        <div>
            <button type="submit" class="btn btn-success">Ajukan</button>
            <a href="{{ route('requests.show') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function addNonconformityInput() {
        const container = document.getElementById('nonconformity-container');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'source_of_nonconformity[]';
        input.setAttribute('list', 'nonconformity-options');
        input.className = 'form-control mb-2';
        container.insertBefore(input, container.querySelector('button'));
    }
</script>
@endpush