<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showUsers()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function showCreate()
    {
        return view('users.create');
    }

    public function handleCreate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:MPS,QQ,SSGA,LM,Distr,CR,HSSE,ITM',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.show')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function showEdit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function handleEdit(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',
            'password' => 'nullable|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.show')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function handleDelete(User $user)
    {
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
