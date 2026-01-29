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

        // Filtro por planta (importante para histórico con múltiples plantas)
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

        // Ordenar por fecha de término (más recientes primero) o fecha de creación
        $orders = $query->latest('completion_date')->latest('created_at')->paginate(20);
        
        // Obtener todas las plantas para el filtro
        $plants = Plant::where('is_active', true)->get();
        
        return view('processing.production_orders.index', compact('orders', 'plants'));
    }

    // COMENTADO: Funcionalidad de crear deshabilitada - ahora es solo histórico
    /*
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
    */

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
        $productionOrder->refresh();

        // Descarte recuperable: entre lo que se manda (order_quantity_kg) y lo que se produce (produced_kilos)
        $orderQty = (float) $productionOrder->order_quantity_kg;
        $produced = (float) ($productionOrder->produced_kilos ?? 0);
        $impliedDiscard = round(max(0, $orderQty - $produced), 2);
        if ($impliedDiscard > 0) {
            $currentStatus = $productionOrder->discard_status;
            if (in_array($currentStatus, [null, 'pending'], true)) {
                $productionOrder->update([
                    'discard_kg' => $impliedDiscard,
                    'discard_status' => 'pending',
                    'discard_reason' => $productionOrder->discard_reason ?: 'Diferencia entre cantidad ordenada y producida (recuperable)',
                ]);
            } else {
                $productionOrder->update(['discard_kg' => $impliedDiscard]);
            }
        } else {
            if (in_array($productionOrder->discard_status, [null, 'pending'], true)) {
                $productionOrder->update(['discard_kg' => 0, 'discard_status' => 'pending', 'discard_reason' => null]);
            }
        }

        $productionOrder->calculateDelay();

        return redirect()->route('processing.production-orders.index')
            ->with('success', 'Orden de producción actualizada exitosamente.');
    }

    public function destroy(PlantProductionOrder $productionOrder)
    {
        $productionOrder->delete();
        return redirect()->route('processing.production-orders.index')
            ->with('success', 'Orden de producción eliminada exitosamente');
    }
}
