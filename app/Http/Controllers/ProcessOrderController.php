<?php

namespace App\Http\Controllers;

use App\Models\ProcessOrder;
use App\Models\ProcessOrderSupply;
use App\Models\PlantProductionOrder;
use App\Models\Plant;
use App\Models\Supplier;
use App\Models\Contract;
use App\Models\ProcessedBin;
use App\Models\BinTraceability;
use App\Models\SupplyPurchaseItem;
use App\Mail\ProcessOrderMail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
        $insumosOptions = SupplyPurchaseItem::select('name', 'unit')
            ->groupBy('name', 'unit')
            ->orderBy('name')
            ->get();

        return view('processing.orders.create', compact('plants', 'suppliers', 'contracts', 'insumosOptions'));
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
            'raw_material' => 'nullable|string',
            'product' => 'nullable|string',
            'type' => 'nullable|string',
            'caliber' => 'nullable|string',
            'quality' => 'nullable|string',
            'labeling' => 'nullable|string',
            'packaging' => 'nullable|string',
            'potassium_sorbate' => 'nullable|string',
            'humidity' => 'nullable|string',
            'stone_percentage' => 'nullable|string',
            'oil' => 'nullable|string',
            'damage' => 'nullable|string',
            'plant_print' => 'nullable|string',
            'destination' => 'nullable|string',
            'loading_date' => 'nullable|string',
            'sag' => 'boolean',
            'kilos_sent' => 'nullable|numeric|min:0',
            'kilos_produced' => 'nullable|numeric|min:0',
            'supplies' => 'nullable|array',
            'supplies.*.name' => 'nullable|string|max:255',
            'supplies.*.quantity' => 'nullable|numeric|min:0.01',
            'supplies.*.unit' => 'nullable|string|max:50',
        ]);

        // Calcular fecha de término esperada si se proporcionaron días de producción
        if ($validated['production_days'] && $validated['order_date']) {
            $orderDate = Carbon::parse($validated['order_date']);
            $validated['expected_completion_date'] = $orderDate->copy()->addDays($validated['production_days'])->format('Y-m-d');
        }

        $order = ProcessOrder::create($validated);

        if (!empty($validated['supplies'])) {
            foreach ($validated['supplies'] as $row) {
                if (!empty($row['name']) && isset($row['quantity']) && (float) $row['quantity'] > 0) {
                    $order->supplies()->create([
                        'name' => $row['name'],
                        'quantity' => $row['quantity'],
                        'unit' => $row['unit'] ?? 'unidad',
                    ]);
                }
            }
        }

        // Crear orden de producción vinculada para que aparezca en /processing/production-orders
        $orderNumberProduction = $order->order_number;
        $exists = PlantProductionOrder::where('order_number', $orderNumberProduction)->exists();
        if ($exists) {
            $orderNumberProduction = $order->order_number . '-' . $order->id;
        }
        PlantProductionOrder::create([
            'process_order_id' => $order->id,
            'plant_id' => $order->plant_id,
            'contract_id' => $order->contract_id,
            'order_number' => $orderNumberProduction,
            'product' => $order->product,
            'output_caliber' => $order->caliber,
            'order_quantity_kg' => $order->quantity ?? 0,
            'completion_date' => $order->expected_completion_date,
            'status' => 'pending',
        ]);
        
        // Cargar relaciones necesarias para el PDF
        $order->load(['plant', 'supplier']);

        // Generar PDF automáticamente
        $pdf = Pdf::loadView('processing.orders.pdf', compact('order'));
        $fileName = 'Orden_Proceso_' . $order->order_number . '.pdf';

        return $pdf->download($fileName);
    }

    public function show(ProcessOrder $order)
    {
        $order->load(['plant.contacts', 'supplier', 'invoices', 'supplies']);
        return view('processing.orders.show', compact('order'));
    }

    /**
     * Generar preview PDF de la orden
     */
    public function previewPdf(ProcessOrder $order)
    {
        $order->load(['plant', 'supplier']);
        $pdf = Pdf::loadView('processing.orders.pdf', compact('order'));
        return $pdf->stream('Orden_Proceso_' . $order->order_number . '.pdf');
    }

    /**
     * Enviar orden por email a la planta
     */
    public function sendToPlant(Request $request, ProcessOrder $order)
    {
        $request->validate([
            'contact_email' => 'required|email',
            'manual_email' => 'nullable|email',
            'contact_name' => 'nullable|string',
        ]);

        try {
            $order->load(['plant', 'supplier']);
            
            // Usar email manual si está presente, sino el del select
            $emailToSend = $request->manual_email ?: $request->contact_email;
            
            Mail::to($emailToSend)->send(new ProcessOrderMail($order));
            
            return back()->with('success', 'Orden enviada exitosamente a ' . $emailToSend);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }

    public function edit(ProcessOrder $order)
    {
        $plants = Plant::where('is_active', true)->get();
        $suppliers = Supplier::all();
        $contracts = Contract::whereIn('status', ['active', 'completed'])->get();
        $insumosOptions = SupplyPurchaseItem::select('name', 'unit')->groupBy('name', 'unit')->orderBy('name')->get();
        $order->load('supplies');

        return view('processing.orders.edit', compact('order', 'plants', 'suppliers', 'contracts', 'insumosOptions'));
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
            'completion_week' => 'nullable|integer|min:1|max:56',
            'completion_year' => 'nullable|integer|min:2020|max:2030',
            'actual_completion_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'product_description' => 'nullable|string',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string',
            'notes' => 'nullable|string',
            'raw_material' => 'nullable|string',
            'product' => 'nullable|string',
            'type' => 'nullable|string',
            'caliber' => 'nullable|string',
            'quality' => 'nullable|string',
            'labeling' => 'nullable|string',
            'packaging' => 'nullable|string',
            'potassium_sorbate' => 'nullable|string',
            'humidity' => 'nullable|string',
            'stone_percentage' => 'nullable|string',
            'oil' => 'nullable|string',
            'damage' => 'nullable|string',
            'plant_print' => 'nullable|string',
            'destination' => 'nullable|string',
            'loading_date' => 'nullable|string',
            'sag' => 'boolean',
            'kilos_sent' => 'nullable|numeric|min:0',
            'kilos_produced' => 'nullable|numeric|min:0',
            'vehicle_plate' => 'nullable|string|max:20',
            'shipment_date' => 'nullable|date',
            'shipment_time' => 'nullable|date_format:H:i',
            'supplies' => 'nullable|array',
            'supplies.*.name' => 'nullable|string|max:255',
            'supplies.*.quantity' => 'nullable|numeric|min:0.01',
            'supplies.*.unit' => 'nullable|string|max:50',
            'labeling_attachment' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp,pdf|max:5120',
        ]);

        if ($request->hasFile('labeling_attachment')) {
            if ($order->labeling_attachment && Storage::disk('public')->exists($order->labeling_attachment)) {
                Storage::disk('public')->delete($order->labeling_attachment);
            }
            $path = $request->file('labeling_attachment')->store('process_orders/labeling', 'public');
            $validated['labeling_attachment'] = $path;
        }

        // Calcular fecha de término esperada: por semana/año o por días de producción
        if (!empty($validated['completion_week']) && !empty($validated['completion_year'])) {
            $d = new \DateTime();
            $d->setISODate((int) $validated['completion_year'], (int) $validated['completion_week'], 1); // Lunes de esa semana
            $validated['expected_completion_date'] = $d->format('Y-m-d');
        } elseif (!empty($validated['production_days']) && !empty($validated['order_date'])) {
            $orderDate = Carbon::parse($validated['order_date']);
            $validated['expected_completion_date'] = $orderDate->copy()->addDays($validated['production_days'])->format('Y-m-d');
        }

        $order->update($validated);

        $order->supplies()->delete();
        if (!empty($validated['supplies'])) {
            foreach ($validated['supplies'] as $row) {
                if (!empty($row['name']) && isset($row['quantity']) && (float) $row['quantity'] > 0) {
                    $order->supplies()->create([
                        'name' => $row['name'],
                        'quantity' => $row['quantity'],
                        'unit' => $row['unit'] ?? 'unidad',
                    ]);
                }
            }
        }

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
