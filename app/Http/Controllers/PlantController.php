<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function index()
    {
        $plants = Plant::latest()->get();
        return view('processing.plants.index', compact('plants'));
    }

    public function create()
    {
        return view('processing.plants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:plants,code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'tax_id' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account_type' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
        ]);

        Plant::create($validated);

        return redirect()->route('processing.plants.index')
            ->with('success', 'Planta creada exitosamente');
    }

    public function show(Plant $plant)
    {
        $plant->load('processOrders');
        return view('processing.plants.show', compact('plant'));
    }

    public function edit(Plant $plant)
    {
        return view('processing.plants.edit', compact('plant'));
    }

    public function update(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:plants,code,' . $plant->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'tax_id' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account_type' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
        ]);

        $plant->update($validated);

        return redirect()->route('processing.plants.index')
            ->with('success', 'Planta actualizada exitosamente');
    }

    public function destroy(Plant $plant)
    {
        $plant->delete();
        return redirect()->route('processing.plants.index')
            ->with('success', 'Planta eliminada exitosamente');
    }
}
