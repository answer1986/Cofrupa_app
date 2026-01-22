<?php

namespace App\Http\Controllers;

use App\Models\PlantProductionOrder;
use App\Models\Plant;
use App\Models\Contract;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlantProductionOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PlantProductionOrder::with(['contract', 'plant']);

        // Filtro por planta
        if ($request->filled('plant_id')) {
            $query->where('plant_id', $request->plant_id);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por retraso
        if ($request->filled('has_delay')) {
            $query->where('has_delay', $request->has_delay == '1');
        }

        $orders = $query->latest('completion_date')->paginate(20);
        
        return view('processing.production_orders.index', compact('orders'));
    }

    public function create()
    {
        $plants = Plant::where('is_active', true)->get();
        $contracts = Contract::whereIn('status', ['active', 'completed'])->get();
        
        return view('processing.production_orders.create', compact('plants', 'contracts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'nullable|exists:contracts,id',
            'plant_id' => 'required|exists:plants,id',
            'order_number' => 'required|string|unique:plant_production_orders,order_number',
            'product' => 'nullable|string',
            'output_caliber' => 'nullable|string',
            'order_quantity_kg' => 'required|numeric|min:0',
            'booking_number' => 'nullable|string',
            'vessel' => 'nullable|string',
            'entry_date' => 'nullable|date',
            'entry_time' => 'nullable',
            'completion_date' => 'nullable|date',
            'completion_time' => 'nullable',
            'production_program' => 'nullable|string',
            'sorbate_solution' => 'nullable|numeric',
            'delay_reason' => 'nullable|string',
            'produced_kilos' => 'nullable|numeric|min:0',
            'nominal_kg_per_hour' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $order = PlantProductionOrder::create($validated);
        
        // Recalcular atraso automáticamente después de guardar
        $order->refresh();
        $order->calculateDelay();

        return redirect()->route('processing.production-orders.index')
            ->with('success', 'Orden de producción creada exitosamente');
    }

    public function show(PlantProductionOrder $productionOrder)
    {
        $productionOrder->load(['contract', 'plant']);
        return view('processing.production_orders.show', compact('productionOrder'));
    }

    public function edit(PlantProductionOrder $productionOrder)
    {
        $plants = Plant::where('is_active', true)->get();
        $contracts = Contract::whereIn('status', ['active', 'completed'])->get();
        
        return view('processing.production_orders.edit', compact('productionOrder', 'plants', 'contracts'));
    }

    public function update(Request $request, PlantProductionOrder $productionOrder)
    {
        $validated = $request->validate([
            'contract_id' => 'nullable|exists:contracts,id',
            'plant_id' => 'required|exists:plants,id',
            'order_number' => 'required|string|unique:plant_production_orders,order_number,' . $productionOrder->id,
            'product' => 'nullable|string',
            'output_caliber' => 'nullable|string',
            'order_quantity_kg' => 'required|numeric|min:0',
            'booking_number' => 'nullable|string',
            'vessel' => 'nullable|string',
            'entry_date' => 'nullable|date',
            'entry_time' => 'nullable',
            'completion_date' => 'nullable|date',
            'completion_time' => 'nullable',
            'production_program' => 'nullable|string',
            'sorbate_solution' => 'nullable|numeric',
            'delay_reason' => 'nullable|string',
            'produced_kilos' => 'nullable|numeric|min:0',
            'nominal_kg_per_hour' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,in_progress,completed,delayed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $productionOrder->update($validated);
        
        // Recalcular atraso automáticamente después de actualizar
        $productionOrder->refresh();
        $productionOrder->calculateDelay();

        return redirect()->route('processing.production-orders.index')
            ->with('success', 'Orden de producción actualizada exitosamente');
    }

    public function destroy(PlantProductionOrder $productionOrder)
    {
        $productionOrder->delete();
        return redirect()->route('processing.production-orders.index')
            ->with('success', 'Orden de producción eliminada exitosamente');
    }
}
