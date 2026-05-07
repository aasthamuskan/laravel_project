<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advisory;
use App\Models\Crop;

/**
 * AdvisoryController
 *
 * Handles CRUD for advisories (Expert role only).
 * Experts can create, edit, and delete advisories via the web UI.
 *
 * GET  /advisories          → index (list all)
 * GET  /advisories/create   → create form
 * POST /advisories          → store
 * GET  /advisories/{id}/edit → edit form
 * PUT  /advisories/{id}     → update
 * DELETE /advisories/{id}   → destroy
 *
 * GET /advisory?crop_id=&season= → public filtered advisory listing (farmers)
 */
class AdvisoryController extends Controller
{
    /**
     * List all advisories (Expert view).
     */
    public function index()
    {
        $advisories = Advisory::with('crop')
            ->orderBy('crop_id')
            ->orderBy('season')
            ->get();

        return view('advisories.index', compact('advisories'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $crops    = Crop::orderBy('name')->get();
        $seasons  = ['Spring', 'Summer', 'Monsoon', 'Winter'];
        $conditions = ['Clear', 'Cloudy', 'Rainy', 'Stormy', 'Cold'];

        return view('advisories.create', compact('crops', 'seasons', 'conditions'));
    }

    /**
     * Store a new advisory.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'crop_id'           => 'required|integer|exists:crops,id',
            'season'            => 'required|in:Spring,Summer,Monsoon,Winter',
            'weather_condition' => 'required|in:Clear,Cloudy,Rainy,Stormy,Cold',
            'advice'            => 'required|string|min:10|max:1000',
        ]);

        Advisory::create($validated);

        return redirect()
            ->route('advisories.index')
            ->with('success', 'Advisory created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(Advisory $advisory)
    {
        $crops      = Crop::orderBy('name')->get();
        $seasons    = ['Spring', 'Summer', 'Monsoon', 'Winter'];
        $conditions = ['Clear', 'Cloudy', 'Rainy', 'Stormy', 'Cold'];

        return view('advisories.edit', compact('advisory', 'crops', 'seasons', 'conditions'));
    }

    /**
     * Update an advisory.
     */
    public function update(Request $request, Advisory $advisory)
    {
        $validated = $request->validate([
            'crop_id'           => 'required|integer|exists:crops,id',
            'season'            => 'required|in:Spring,Summer,Monsoon,Winter',
            'weather_condition' => 'required|in:Clear,Cloudy,Rainy,Stormy,Cold',
            'advice'            => 'required|string|min:10|max:1000',
        ]);

        $advisory->update($validated);

        return redirect()
            ->route('advisories.index')
            ->with('success', 'Advisory updated successfully.');
    }

    /**
     * Delete an advisory (Expert or Admin).
     */
    public function destroy(Advisory $advisory)
    {
        $advisory->delete();

        return redirect()
            ->route('advisories.index')
            ->with('success', 'Advisory deleted successfully.');
    }

    /**
     * GET /advisory?crop_id=&season=
     * Public endpoint: farmers filter advisories by crop and season.
     */
    public function filter(Request $request)
    {
        $request->validate([
            'crop_id' => 'nullable|integer|exists:crops,id',
            'season'  => 'nullable|string|in:Spring,Summer,Monsoon,Winter',
        ]);

        $query = Advisory::with('crop');

        if ($request->filled('crop_id')) {
            $query->where('crop_id', $request->crop_id);
        }

        if ($request->filled('season')) {
            $query->where('season', $request->season);
        }

        $advisories = $query->get();
        $crops      = Crop::orderBy('name')->get();
        $seasons    = ['Spring', 'Summer', 'Monsoon', 'Winter'];

        return view('advisories.filter', compact('advisories', 'crops', 'seasons'));
    }
}
