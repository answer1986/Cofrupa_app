<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Machine;
use Illuminate\Http\Request;

class MachineMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with(['machine', 'user']);

        // Filtros opcionales
        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        if ($request->filled('maintenance_type')) {
            $query->where('maintenance_type', $request->maintenance_type);
        }

        if ($request->filled('periodicity')) {
            $query->where('periodicity', $request->periodicity);
        }

        $maintenances = $query->latest('maintenance_date')->paginate(20);
        $machines = Machine::where('status', 'active')->get();

        return view('processing.maintenances.index', compact('maintenances', 'machines'));
    }

    public function create()
    {
        $machines = Machine::where('status', 'active')->get();
        return view('processing.maintenances.create', compact('machines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'maintenance_type' => 'required|in:preventive,corrective,predictive,emergency',
            'periodicity' => 'required|in:daily,weekly,monthly,quarterly,biannual,annual,as_needed',
            'maintenance_date' => 'required|date',
            'next_maintenance_date' => 'nullable|date|after:maintenance_date',
            'description' => 'nullable|string',
            'observations' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'technician' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        Maintenance::create($validated);

        return redirect()->route('processing.maintenances.index')
            ->with('success', 'Mantención registrada exitosamente');
    }

    public function edit(Maintenance $maintenance)
    {
        $machines = Machine::where('status', 'active')->get();
        return view('processing.maintenances.edit', compact('maintenance', 'machines'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $validated = $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'maintenance_type' => 'required|in:preventive,corrective,predictive,emergency',
            'periodicity' => 'required|in:daily,weekly,monthly,quarterly,biannual,annual,as_needed',
            'maintenance_date' => 'required|date',
            'next_maintenance_date' => 'nullable|date|after:maintenance_date',
            'description' => 'nullable|string',
            'observations' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'technician' => 'nullable|string|max:255',
        ]);

        $maintenance->update($validated);

        return redirect()->route('processing.maintenances.index')
            ->with('success', 'Mantención actualizada exitosamente');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('processing.maintenances.index')
            ->with('success', 'Mantención eliminada exitosamente');
    }
}
