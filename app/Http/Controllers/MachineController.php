<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::latest()->get();
        return view('processing.machines.index', compact('machines'));
    }

    public function create()
    {
        return view('processing.machines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:machines,code',
            'type' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,maintenance,retired',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        Machine::create($validated);

        return redirect()->route('processing.machines.index')
            ->with('success', 'Máquina creada exitosamente');
    }

    public function show(Machine $machine)
    {
        $machine->load('maintenances.user');
        return view('processing.machines.show', compact('machine'));
    }

    public function edit(Machine $machine)
    {
        return view('processing.machines.edit', compact('machine'));
    }

    public function update(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:machines,code,' . $machine->id,
            'type' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,maintenance,retired',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $machine->update($validated);

        return redirect()->route('processing.machines.index')
            ->with('success', 'Máquina actualizada exitosamente');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();
        return redirect()->route('processing.machines.index')
            ->with('success', 'Máquina eliminada exitosamente');
    }
}
