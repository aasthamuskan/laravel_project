@extends('layouts.app-farm')
@section('title', 'Admin Overview')

@section('content')
<div class="container">
    <div style="margin-bottom: 2rem;">
        <h1 class="page-title">Admin Overview</h1>
        <p class="page-subtitle">System statistics and quick links.</p>
    </div>

    {{-- Stats Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        @foreach([
            ['👥', 'Total Users',   $stats['users'],      '#e0f2fe', '#0369a1'],
            ['🌾', 'Farmers',       $stats['farmers'],    '#dcfce7', '#166534'],
            ['🔬', 'Experts',       $stats['experts'],    '#dbeafe', '#1d4ed8'],
            ['🌱', 'Crops',         $stats['crops'],      '#fef3c7', '#92400e'],
            ['📋', 'Advisories',    $stats['advisories'], '#f3e8ff', '#7c3aed'],
        ] as [$icon, $label, $val, $bg, $color])
        <div class="card" style="border-left: 4px solid {{ $color }};">
            <div class="card-body" style="padding: 1.2rem;">
                <div style="font-size: 1.8rem;">{{ $icon }}</div>
                <div style="font-size: 2rem; font-weight: 800; color: {{ $color }}; margin: 4px 0;">{{ $val }}</div>
                <div style="font-size: 0.82rem; color: var(--gray-400); font-weight: 600;">{{ $label }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Quick Links --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div class="card">
            <div class="card-header">🚀 Quick Actions</div>
            <div class="card-body" style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">👥 Manage Users</a>
                <a href="{{ route('admin.advisories') }}" class="btn btn-secondary">📋 Manage Advisories</a>
            </div>
        </div>
        <div class="card">
            <div class="card-header">ℹ️ System Info</div>
            <div class="card-body">
                <table>
                    <tr><th style="padding:8px 12px;">Laravel</th><td style="padding:8px 12px;">{{ app()->version() }}</td></tr>
                    <tr><th style="padding:8px 12px;">PHP</th><td style="padding:8px 12px;">{{ PHP_VERSION }}</td></tr>
                    <tr><th style="padding:8px 12px;">Environment</th><td style="padding:8px 12px;">{{ app()->environment() }}</td></tr>
                    <tr><th style="padding:8px 12px;">Cache Driver</th><td style="padding:8px 12px;">{{ config('cache.default') }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
