@extends('layouts.app')

@section('content')
    <h2 class="mb-4">Tambah Pengguna</h2>

    <form action="{{ route('users.create.handle') }}" method="POST" class="vstack gap-3">
        @csrf
        <div>
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>
        <div>
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div>
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="">-- Pilih Role --</option>
                @foreach(['MPS', 'QQ', 'SSGA', 'LM', 'Distr', 'CR', 'HSSE', 'ITM'] as $role)
                    <option value="{{ $role }}" @if(old('role') == $role) selected @endif>{{ $role }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('users.show') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection