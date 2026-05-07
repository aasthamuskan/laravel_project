@extends('layouts.app-farm')
@section('title', 'New Advisory')

@section('content')
<div class="container" style="max-width: 680px;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('advisories.index') }}" style="color: var(--gray-400); text-decoration: none; font-size: 0.9rem;">← Back to Advisories</a>
        <h1 class="page-title" style="margin-top: 8px;">Create Advisory</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('advisories.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="crop_id">Crop *</label>
                    <select id="crop_id" name="crop_id" class="form-control" required>
                        <option value="">— Select crop —</option>
                        @foreach($crops as $crop)
                            <option value="{{ $crop->id }}" {{ old('crop_id') == $crop->id ? 'selected' : '' }}>{{ $crop->name }}</option>
                        @endforeach
                    </select>
                    @error('crop_id') <p style="color:var(--red-500);font-size:0.82rem;margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="form-group">
                        <label class="form-label" for="season">Season *</label>
                        <select id="season" name="season" class="form-control" required>
                            <option value="">— Select —</option>
                            @foreach($seasons as $s)
                                <option value="{{ $s }}" {{ old('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('season') <p style="color:var(--red-500);font-size:0.82rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="weather_condition">Weather Condition *</label>
                        <select id="weather_condition" name="weather_condition" class="form-control" required>
                            <option value="">— Select —</option>
                            @foreach($conditions as $c)
                                <option value="{{ $c }}" {{ old('weather_condition') == $c ? 'selected' : '' }}>{{ $c }}</option>
                            @endforeach
                        </select>
                        @error('weather_condition') <p style="color:var(--red-500);font-size:0.82rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="advice">Advisory Text * <span style="color:var(--gray-400); font-weight:400;">(10–1000 characters)</span></label>
                    <textarea id="advice" name="advice" class="form-control" rows="5" required placeholder="Provide specific, actionable advice for this crop in this weather condition...">{{ old('advice') }}</textarea>
                    @error('advice') <p style="color:var(--red-500);font-size:0.82rem;margin-top:4px;">{{ $message }}</p> @enderror
                </div>

                <div style="display:flex; gap:12px; margin-top:8px;">
                    <button type="submit" class="btn btn-primary">💾 Save Advisory</button>
                    <a href="{{ route('advisories.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
