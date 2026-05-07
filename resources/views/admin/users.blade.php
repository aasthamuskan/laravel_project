@extends('layouts.app-farm')
@section('title', 'Manage Users')

@section('content')
<div class="container">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title">User Management</h1>
            <p class="page-subtitle">{{ $users->count() }} registered users.</p>
        </div>
        <a href="{{ route('admin.overview') }}" class="btn btn-secondary">← Overview</a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span style="font-size:0.75rem; color:var(--gray-400);"> (you)</span>
                            @endif
                        </td>
                        <td style="color: var(--gray-500);">{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-{{ strtolower($user->role) }}">{{ $user->role }}</span>
                        </td>
                        <td style="color: var(--gray-400); font-size: 0.85rem;">{{ $user->created_at->format('M d, Y') }}</td>
                        <td style="text-align:right; white-space: nowrap;">
                            {{-- Role change form --}}
                            <form action="{{ route('admin.users.role', $user->id) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <select name="role" class="form-control" style="display:inline-block; width:auto; padding: 4px 8px; font-size:0.82rem; margin-right:4px;" onchange="this.form.submit()">
                                    @foreach(['Farmer','Expert','Admin'] as $role)
                                        <option {{ $user->role === $role ? 'selected' : '' }}>{{ $role }}</option>
                                    @endforeach
                                </select>
                            </form>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">🗑️</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
