<?php

namespace App\Http\Controllers;

use App\Models\ProcessOrder;
use App\Models\Plant;
use App\Models\Supplier;
use App\Models\Contract;
use App\Models\ProcessedBin;
use App\Models\BinTraceability;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProcessOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ProcessOrder::with(['plant', 'supplier', 'invoices']);

        // Filtro por planta
        if ($request->filled('plant_id')) {
            $query->where('plant_id', $request->plant_id);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest('order_date')->paginate(20);
        
        return view('processing.orders.index', compact('orders'));
    }

    public function create()
    {
        $plants = Plant::where('is_active', true)->get();
        $suppliers = Supplier::all();
        $contracts = Contract::whereIn('status', ['active', 'completed'])->get();
        
        return view('processing.orders.create', compact('plants', 'suppliers', 'contracts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plant_id' => 'required|exists:plants,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'order_number' => 'required|string|unique:process_orders,order_number',
            'csg_code' => 'nullable|string',
            'production_days' => 'nullable|integer|min:1',
            'order_date' => 'required|date',
            'expected_completion_date' => 'nullable|date|after_or_equal:order_date',
            'product_description' => 'nullable|string',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Calcular fecha de término esperada si se proporcionaron días de producción
        if ($validated['production_days'] && $validated['order_date']) {
            $orderDate = Carbon::parse($validated['order_date']);
            $validated['expected_completion_date'] = $orderDate->copy()->addDays($validated['production_days'])->format('Y-m-d');
        }

        ProcessOrder::create($validated);

        return redirect()->route('processing.orders.index')
            ->with('success', 'Orden de proceso creada exitosamente');
    }

    public function show(ProcessOrder $order)
    {
        $order->load(['plant', 'supplier', 'invoices']);
        return view('processing.orders.show', compact('order'));
    }

    public function edit(ProcessOrder $order)
    {
        $plants = Plant::where('is_active', true)->get();
        $suppliers = Supplier::all();
        $contracts = Contract::whereIn('status', ['active', 'completed'])->get();
        
        return view('processing.orders.edit', compact('order', 'plants', 'suppliers', 'contracts'));
    }

    public function update(Request $request, ProcessOrder $order)
    {
        $validated = $request->validate([
            'plant_id' => 'required|exists:plants,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'order_number' => 'required|string|unique:process_orders,order_number,' . $order->id,
            'csg_code' => 'nullable|string',
            'production_days' => 'nullable|integer|min:1',
            'order_date' => 'required|date',
            'expected_completion_date' => 'nullable|date|after_or_equal:order_date',
            'actual_completion_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'product_description' => 'nullable|string',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Calcular fecha de término esperada si se proporcionaron días de producción
        if ($validated['production_days'] && $validated['order_date']) {
            $orderDate = Carbon::parse($validated['order_date']);
            $validated['expected_completion_date'] = $orderDate->copy()->addDays($validated['production_days'])->format('Y-m-d');
        }

        $order->update($validated);

        return redirect()->route('processing.orders.index')
            ->with('success', 'Orden de proceso actualizada exitosamente');
    }

    public function destroy(ProcessOrder $order)
    {
        $order->delete();
        return redirect()->route('processing.orders.index')
            ->with('success', 'Orden de proceso eliminada exitosamente');
    }

    public function updateProgress(Request $request, ProcessOrder $order)
    {
        $validated = $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
        ]);

        $order->update($validated);

        // Si el progreso es 100%, marcar como completado
        if ($validated['progress_percentage'] == 100 && $order->status != 'completed') {
            $order->update([
                'status' => 'completed',
                'actual_completion_date' => Carbon::now()->format('Y-m-d'),
            ]);
        }

        return redirect()->back()->with('success', 'Progreso actualizado exitosamente');
    }

    public function sendAlert(ProcessOrder $order)
    {
        // Aquí se implementaría la lógica para enviar alertas
        $order->update(['alert_sent' => true]);
        
        return redirect()->back()->with('success', 'Alerta enviada exitosamente');
    }

    /**
     * Asignar tarjas (processed bins) a una orden de procesamiento
     */
    public function assignTarjas(Request $request, ProcessOrder $order)
    {
        $validated = $request->validate([
            'tarja_ids' => 'required|array|min:1',
            'tarja_ids.*' => 'exists:processed_bins,id',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:0.01',
        ]);

        $tarjaIds = $validated['tarja_ids'];
        $quantities = $validated['quantities'];

        // Sincronizar tarjas con la orden
        $syncData = [];
        foreach ($tarjaIds as $index => $tarjaId) {
            $quantity = $quantities[$index] ?? 0;
            if ($quantity > 0) {
                $syncData[$tarjaId] = ['quantity_kg' => $quantity];
                
                // Registrar trazabilidad: bin -> orden de procesamiento
                BinTraceability::record(BinTraceability::OPERATION_PROCESSING, [
                    'source_bin_id' => $tarjaId,
                    'process_order_id' => $order->id,
                    'weight_kg' => $quantity,
                    'operation_date' => now(),
                    'notes' => "Asignado a orden de procesamiento: {$order->order_number}",
                ]);
            }
        }

        $order->tarjas()->sync($syncData);

        return redirect()->back()->with('success', 'Tarjas asignadas exitosamente a la orden.');
    }
}
