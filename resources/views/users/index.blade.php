@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="hero">
    <div>
        <span class="eyebrow">ADMINISTRATION</span>
        <h1>Users</h1>
        <p>Manage Admin and Staff access securely on the server.</p>
    </div>
</div>

<div class="grid-2">
    <section class="card">
        <div class="card-head"><h2>{{ $editing ? 'Edit User' : 'Add User' }}</h2></div>
        <form method="POST" action="{{ $editing ? route('users.update', $editing) : route('users.store') }}" class="form-grid">
            @csrf
            @if($editing) @method('PUT') @endif

            @if($editing)
                <label>Username<input type="text" value="{{ $editing->username }}" disabled></label>
            @else
                <x-field label="Username" name="username" required />
            @endif

            <x-field label="Full Name" name="name" :value="$editing->name ?? null" required />

            <x-field label="Role" name="role" type="select" required>
                @foreach(['staff' => 'Staff', 'admin' => 'Admin'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('role', $editing->role ?? 'staff') === $value)>{{ $label }}</option>
                @endforeach
            </x-field>

            @if($editing)
                <x-field label="New Password (optional)" name="password" type="password" />
            @else
                <x-field label="Password" name="password" type="password" required />
            @endif

            <button type="submit" class="btn primary">{{ $editing ? 'Save Changes' : 'Add User' }}</button>
            @if($editing)
                <a class="btn ghost" href="{{ route('users.index') }}">Cancel</a>
            @endif
        </form>
    </section>

    <section class="card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Username</th><th>Full Name</th><th>Role</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($users as $u)
                    <tr>
                        <td><b>{{ $u->username }}</b></td>
                        <td>{{ $u->name }}</td>
                        <td><span class="badge {{ $u->role === 'admin' ? 'info' : 'success' }}">{{ ucfirst($u->role) }}</span></td>
                        <td>
                            <a class="btn tiny" href="{{ route('users.edit', $u) }}">Edit</a>
                            {{-- Akun yang sedang login tidak punya tombol Delete --}}
                            @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $u) }}" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn tiny danger-btn">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
