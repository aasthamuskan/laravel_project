@extends('layouts.app-farm')
@section('title', 'Admin Advisories')

@section('content')
<div class="container">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title">Advisory Administration</h1>
            <p class="page-subtitle">Review and manage all system advisories.</p>
        </div>
        <a href="{{ route('admin.overview') }}" class="btn btn-secondary">← Overview</a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($advisories->isEmpty())
                <div style="text-align:center; padding:3rem; color:var(--gray-400);">No advisories in the system.</div>
            @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Crop</th>
                        <th>Season</th>
                        <th>Condition</th>
                        <th>Advice</th>
                        <th>Added</th>
                        <th style="text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($advisories as $advisory)
                    <tr>
                        <td style="color:var(--gray-400); font-size:0.82rem;">{{ $advisory->id }}</td>
                        <td><strong>{{ $advisory->crop->name }}</strong></td>
                        <td><span class="badge" style="background:#e0f2fe; color:#0369a1;">{{ $advisory->season }}</span></td>
                        <td><span class="badge" style="background:#fef3c7; color:#92400e;">{{ $advisory->weather_condition }}</span></td>
                        <td style="max-width:300px; color:var(--gray-600); font-size:0.88rem;">{{ Str::limit($advisory->advice, 90) }}</td>
                        <td style="color:var(--gray-400); font-size:0.82rem;">{{ $advisory->created_at->format('M d') }}</td>
                        <td style="text-align:right;">
                            <form action="{{ route('admin.advisories.destroy', $advisory->id) }}" method="POST" onsubmit="return confirm('Delete this advisory?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">🗑️ Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
