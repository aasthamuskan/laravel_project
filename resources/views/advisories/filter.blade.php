@extends('layouts.app-farm')
@section('title', 'Browse Advisories')

@section('content')
<div class="container">
    <div style="margin-bottom: 2rem;">
        <h1 class="page-title">Browse Farming Advisories</h1>
        <p class="page-subtitle">Filter by crop and season to find relevant guidance.</p>
    </div>

    {{-- Filter Form --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <form action="{{ route('advisory.filter') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
                <div style="flex: 1; min-width: 200px;">
                    <label class="form-label" for="crop_id">Crop</label>
                    <select id="crop_id" name="crop_id" class="form-control">
                        <option value="">All Crops</option>
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ request('crop_id') == $crop->id ? 'selected' : '' }}>{{ $crop->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label class="form-label" for="season">Season</label>
                    <select id="season" name="season" class="form-control">
                        <option value="">All Seasons</option>
                        @foreach($seasons as $s)
                            <option value="{{ $s }}" {{ request('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('advisory.filter') }}" class="btn btn-secondary">Reset</a>
            </form>
        </div>
    </div>

    {{-- Results --}}
    @if($advisories->isEmpty())
        <div class="card" style="text-align: center; padding: 3rem;">
            <div style="font-size: 2.5rem; margin-bottom: 10px;">🔍</div>
            <p style="color: var(--gray-400);">No advisories match your filters.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1.2rem;">
            @foreach($advisories as $advisory)
            <div class="card">
                <div class="card-header" style="justify-content: space-between;">
                    <span>{{ $advisory->crop->name }}</span>
                    <div style="display: flex; gap: 6px;">
                        <span class="badge" style="background:#e0f2fe; color:#0369a1;">{{ $advisory->season }}</span>
                        <span class="badge" style="background:#fef3c7; color:#92400e;">{{ $advisory->weather_condition }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <p style="color: var(--gray-600); line-height: 1.65; font-size: 0.92rem;">{{ $advisory->advice }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <p style="color: var(--gray-400); font-size: 0.85rem; margin-top: 1.5rem;">
            Showing {{ $advisories->count() }} advisories.
        </p>
    @endif
</div>
@endsection
