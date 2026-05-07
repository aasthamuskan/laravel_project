@extends('layouts.app-farm')
@section('title', 'Manage Advisories')

@section('content')
<div class="container">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
        <div>
            <h1 class="page-title">Advisory Management</h1>
            <p class="page-subtitle">Create and manage crop-weather advisory entries.</p>
        </div>
        <a href="{{ route('advisories.create') }}" class="btn btn-primary">+ New Advisory</a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            @if($advisories->isEmpty())
                <div style="text-align:center; padding: 3rem; color: var(--gray-400);">
                    <div style="font-size:2.5rem; margin-bottom:10px;">📭</div>
                    <p>No advisories yet. <a href="{{ route('advisories.create') }}" style="color:var(--green-600);">Create the first one.</a></p>
                </div>
            @else
            <table>
                <thead>
                    <tr>
                        <th>Crop</th>
                        <th>Season</th>
                        <th>Weather Condition</th>
                        <th>Advisory Snippet</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($advisories as $advisory)
                    <tr>
                        <td><strong>{{ $advisory->crop->name }}</strong></td>
                        <td>
                            <span class="badge" style="background:#e0f2fe; color:#0369a1;">{{ $advisory->season }}</span>
                        </td>
                        <td>
                            <span class="badge" style="
                                background: {{ match($advisory->weather_condition) {
                                    'Rainy'  => '#dbeafe',
                                    'Stormy' => '#fce7f3',
                                    'Clear'  => '#dcfce7',
                                    'Cold'   => '#f1f5f9',
                                    default  => '#fef3c7'
                                } }};
                                color: {{ match($advisory->weather_condition) {
                                    'Rainy'  => '#1d4ed8',
                                    'Stormy' => '#9d174d',
                                    'Clear'  => '#166534',
                                    'Cold'   => '#334155',
                                    default  => '#92400e'
                                } }};
                            ">{{ $advisory->weather_condition }}</span>
                        </td>
                        <td style="max-width: 380px; color: var(--gray-600);">
                            {{ Str::limit($advisory->advice, 100) }}
                        </td>
                        <td style="text-align:right; white-space: nowrap;">
                            <a href="{{ route('advisories.edit', $advisory->id) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
                            <form action="{{ route('advisories.destroy', $advisory->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this advisory?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">🗑️</button>
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
