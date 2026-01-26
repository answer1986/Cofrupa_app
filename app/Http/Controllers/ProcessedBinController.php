<?php

namespace App\Http\Controllers;

use App\Models\ProcessedBin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProcessedBinController extends Controller
{
    /**
     * Display a listing of processed bins.
     */
    public function index()
    {
        $processedBins = ProcessedBin::with(['supplier', 'purchase'])
            ->whereIn('status', ['received', 'processed'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('processed_bins.index', compact('processedBins'));
    }

    /**
     * Show the form for creating a new processed bin.
     */
    public function create()
    {
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        return view('processed_bins.create', compact('suppliers'));
    }

    /**
     * Store a newly created processed bin in storage.
     */
    public function store(Request $request)
    {
        // This might not be used, but included for completeness
        $request->validate([
            // Add validation rules as needed
        ]);

        // Implementation would go here
    }

    /**
     * Display the specified processed bin.
     */
    public function show($id)
    {
        $processedBin = ProcessedBin::with(['supplier', 'purchase'])->findOrFail($id);

        return view('processed_bins.show', compact('processedBin'));
    }

    /**
     * Show the form for editing the specified processed bin.
     */
    public function edit($id)
    {
        $processedBin = ProcessedBin::findOrFail($id);

        return view('processed_bins.edit', compact('processedBin'));
    }

    /**
     * Update the specified processed bin in storage.
     */
    public function update(Request $request, $id)
    {
        $processedBin = ProcessedBin::findOrFail($id);

        $request->validate([
            'exit_date' => 'nullable|date',
            'destination' => 'nullable|string|max:255',
            'guide_number' => 'nullable|string|max:100',
            'status' => 'required|in:received,processed,shipped,delivered',
        ]);

        $processedBin->update($request->only(['exit_date', 'destination', 'guide_number', 'status']));

        return redirect()->route('processed_bins.show', $processedBin)
            ->with('success', 'Bin procesado actualizado exitosamente.');
    }

    /**
     * Remove the specified processed bin from storage.
     */
    public function destroy($id)
    {
        $processedBin = ProcessedBin::findOrFail($id);

        // Check if bin can be deleted (not in use, etc.)
        // Implementation would go here

        $processedBin->delete();

        return redirect()->route('processed_bins.index')
            ->with('success', 'Bin procesado eliminado exitosamente.');
    }
}