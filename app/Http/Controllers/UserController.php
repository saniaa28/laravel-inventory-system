<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', ['users' => User::orderBy('username')->get(), 'editing' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'regex:/^[A-Za-z0-9_.-]{3,50}$/', 'unique:users,username'],
            'name'     => ['required', 'string', 'max:100'],
            'role'     => ['required', Rule::in(['admin', 'staff'])],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'username.regex'  => 'Username must be 3-50 characters and use letters, numbers, dot, underscore or dash.',
            'username.unique' => 'Username already exists.',
            'password.min'    => 'Password must be at least 8 characters.',
        ]);

        // Kolom email wajib di tabel users bawaan Laravel, jadi dibuatkan otomatis
        $data['email'] = $data['username'].'@invora.test';

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User added successfully.');
    }

    public function edit(User $user): View
    {
        return view('users.index', ['users' => User::orderBy('username')->get(), 'editing' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'role'     => ['required', Rule::in(['admin', 'staff'])],
            'password' => ['nullable', 'string', 'min:8'],
        ], ['password.min' => 'Password must be at least 8 characters.']);

        // Admin tidak boleh menurunkan role akunnya sendiri (supaya tidak kehilangan admin)
        if ($user->id === $request->user()->id) {
            $data['role'] = 'admin';
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Aturan bisnis: akun yang pernah mencatat transaksi stok tidak boleh dihapus
        if ($user->stockMovements()->exists()) {
            return back()->with('error', 'User cannot be deleted because this account has stock transaction history.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
