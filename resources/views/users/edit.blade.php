@extends('layouts.app')

@section('title', 'Sesuaikan Pengguna')

@section('content')
    <h2 class="mb-4">Sesuaikan Pengguna</h2>

    <form action="{{ route('users.edit.handle', $user->id) }}" method="POST" class="vstack gap-3">
        @csrf
        @method('PUT')
        <div>
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
        </div>
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
        </div>
        <div>
            <label class="form-label">Password (biarkan kosong jika tidak diubah)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div>
            <label class="form-label">Role</label>
            <select name="role" class="form-control" required>
                @foreach(['MPS', 'QQ', 'SSGA', 'LM', 'Distr', 'CR', 'HSSE', 'ITM'] as $role)
                    <option value="{{ $role }}" @if(old('role', $user->role) == $role) selected @endif>{{ $role }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button class="btn btn-primary">Perbarui</button>
            <a href="{{ route('users.show') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection