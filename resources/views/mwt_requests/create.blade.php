@extends('layouts.app')

@section('title', 'Pengajuan Laporan MWT')

@section('content')
    <h1 class="mb-4 fw-bold">Formulir Pengajuan Laporan MWT</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mwt-requests.create.handle') }}" method="POST" enctype="multipart/form-data" class="vstack gap-3">
        @csrf

        <div>
            <label for="execution_date" class="form-label">Tanggal Pelaksanaan MWT</label>
            <input type="date" id="execution_date" name="execution_date" class="form-control" required>
        </div>

        <div>
            <label for="participants" class="form-label">Pelaksana MWT</label>
            <select id="participants" name="participants[]" class="form-select" multiple required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
            <div class="form-text">Tekan Ctrl / Cmd untuk memilih lebih dari satu.</div>
        </div>

        {{-- Dialogues --}}
        <div>
            <label class="form-label">Hasil Dialog Management Dengan Pekerja Lapangan</label>
            <div id="dialogues-wrapper">
                <div class="input-group mb-2">
                    <input type="text" name="dialogues[]" class="form-control" placeholder="Dialog 1">
                    <button type="button" class="btn btn-outline-secondary remove-field">−</button>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" id="add-dialogue">+ Tambah Dialog</button>
        </div>

        {{-- Positive Findings --}}
        <div>
            <label class="form-label">Temuan Positif</label>
            <div id="positives-wrapper">
                <div class="input-group mb-2">
                    <input type="text" name="positive_findings[]" class="form-control" placeholder="Temuan 1">
                    <button type="button" class="btn btn-outline-secondary remove-field">−</button>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" id="add-positive">+ Tambah Temuan</button>
        </div>

        {{-- Unsafe Conditions --}}
        <div>
            <label class="form-label">Kondisi Tidak Standar (Unsafe Condition)</label>
            <div id="unsafe-wrapper">
                <div class="input-group mb-2">
                    <input type="text" name="unsafe_conditions[]" class="form-control" placeholder="Kondisi 1">
                    <button type="button" class="btn btn-outline-secondary remove-field">−</button>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" id="add-unsafe">+ Tambah Kondisi</button>
        </div>
        <div>
            <label for="documentation" class="form-label">Dokumentasi (opsional)</label>
            <input type="file" name="documentation" id="documentation" class="form-control">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">
                Ajukan Laporan
            </button>
        </div>
    </form>
@endsection
@push('scripts')
<script>
    function createInput(name, placeholder) {
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" name="${name}" class="form-control" placeholder="${placeholder}">
            <button type="button" class="btn btn-outline-secondary remove-field">−</button>
        `;
        return div;
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('add-dialogue').addEventListener('click', () => {
            document.getElementById('dialogues-wrapper').appendChild(createInput('dialogues[]', 'Dialog'));
        });

        document.getElementById('add-positive').addEventListener('click', () => {
            document.getElementById('positives-wrapper').appendChild(createInput('positive_findings[]', 'Temuan'));
        });

        document.getElementById('add-unsafe').addEventListener('click', () => {
            document.getElementById('unsafe-wrapper').appendChild(createInput('unsafe_conditions[]', 'Kondisi'));
        });

        document.body.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-field')) {
                e.target.closest('.input-group').remove();
            }
        });
    });
</script>
@endpush