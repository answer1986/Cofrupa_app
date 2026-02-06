<?php

namespace App\Http\Controllers;

use App\Models\PlantProductionOrder;
use App\Models\Plant;
use App\Models\ProcessedBin;
use Illuminate\Http\Request;

class DiscardController extends Controller
{
    public function index(Request $request)
    {
        $query = PlantProductionOrder::with(['plant', 'contract'])
            ->where('discard_kg', '>', 0);

        // Filtros
        if ($request->filled('discard_status')) {
            $query->where('discard_status', $request->discard_status);
        }

        if ($request->filled('plant_id')) {
            $query->where('plant_id', $request->plant_id);
        }

        if ($request->filled('date_from')) {
            $query->where('completion_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('completion_date', '<=', $request->date_to);
        }

        $discards = $query->latest('completion_date')->paginate(15);

        // Estadísticas (pobladas desde órdenes de producción: orden - producido = descarte recuperable)
        $totalPendingKg = PlantProductionOrder::where('discard_status', 'pending')
            ->where('discard_kg', '>', 0)
            ->sum('discard_kg');

        $totalRecoveredKg = PlantProductionOrder::where('discard_status', 'recovered')
            ->sum('discard_kg');

        $totalDisposedKg = PlantProductionOrder::where('discard_status', 'disposed')
            ->sum('discard_kg');

        $totalDiscardValue = PlantProductionOrder::with('contract')
            ->where('discard_status', 'pending')
            ->where('discard_kg', '>', 0)
            ->get()
            ->sum(function ($order) {
                return $order->discard_kg * ($order->contract->price ?? 0);
            });

        $plants = Plant::all();

        $stats = [
            'total_pending_kg' => $totalPendingKg,
            'total_recovered_kg' => $totalRecoveredKg,
            'total_disposed_kg' => $totalDisposedKg,
            'total_discard_value' => $totalDiscardValue,
        ];

        return view('discards.index', compact('discards', 'stats', 'plants'));
    }

    public function create()
    {
        // Obtener órdenes de producción que no están canceladas
        $productionOrders = PlantProductionOrder::with(['plant'])
            ->whereIn('status', ['pending', 'in_progress', 'completed'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('discards.create', compact('productionOrders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_order_id' => 'required|exists:plant_production_orders,id',
            'discard_kg' => 'required|numeric|min:0.01',
            'discard_humid_kg' => 'nullable|numeric|min:0',
            'discard_stone_kg' => 'nullable|numeric|min:0',
            'discard_other_kg' => 'nullable|numeric|min:0',
            'discard_reason' => 'required|string|max:255',
            'discard_status' => 'required|in:pending,recovered,disposed',
            'recovery_location' => 'required_if:discard_status,recovered|nullable|string|max:255',
            'discard_notes' => 'nullable|string',
        ]);

        $productionOrder = PlantProductionOrder::findOrFail($validated['production_order_id']);

        // Actualizar la orden de producción con el descarte
        $productionOrder->update([
            'discard_kg' => $productionOrder->discard_kg + $validated['discard_kg'],
            'discard_humid_kg' => $productionOrder->discard_humid_kg + ($validated['discard_humid_kg'] ?? 0),
            'discard_stone_kg' => $productionOrder->discard_stone_kg + ($validated['discard_stone_kg'] ?? 0),
            'discard_other_kg' => $productionOrder->discard_other_kg + ($validated['discard_other_kg'] ?? 0),
            'discard_reason' => $validated['discard_reason'],
            'discard_status' => $validated['discard_status'],
            'discard_notes' => $validated['discard_notes'] ?? null,
            'discard_recovery_date' => $validated['discard_status'] !== 'pending' ? now() : null,
        ]);

        // Si el estado es "recovered", crear automáticamente una tarja en el stock
        if ($validated['discard_status'] === 'recovered') {
            $newTarja = ProcessedBin::create([
                'purchase_id' => null,
                'supplier_id' => null,
                'bin_id' => null,
                'tarja_number' => 'DESC-' . $productionOrder->order_number . '-' . now()->format('YmdHis'),
                'gross_weight' => $validated['discard_kg'],
                'net_fruit_weight' => $validated['discard_kg'],
                'calibre' => $productionOrder->output_caliber ?? 'mixto',
                'trash_level' => 4,
                'damage_percentage' => 0,
                'csg_code' => 'DESCARTE',
                'internal_supplier_code' => 'COFRUPA-RECUP',
                'lote' => 'RECUP-' . $productionOrder->order_number,
                'stock_status' => 'available',
                'available_kg' => $validated['discard_kg'],
                'assigned_kg' => 0,
                'used_kg' => 0,
                'location' => $validated['recovery_location'],
                'notes' => 'Descarte recuperado de orden de producción #' . $productionOrder->order_number . '. ' . ($validated['discard_notes'] ?? ''),
            ]);

            $newTarja->generateInitialQrCode();

            return redirect()->route('discards.index')
                ->with('success', "Descarte registrado y recuperado exitosamente. Nueva tarja creada: {$newTarja->tarja_number}");
        }

        return redirect()->route('discards.index')
            ->with('success', 'Descarte registrado exitosamente');
    }

    public function show(PlantProductionOrder $discard)
    {
        $discard->load(['plant', 'contract.client']);
        return view('discards.show', compact('discard'));
    }

    public function recover(Request $request, PlantProductionOrder $discard)
    {
        $validated = $request->validate([
            'recovery_location' => 'required|string|max:255',
            'recovery_notes' => 'nullable|string',
        ]);

        // Actualizar estado del descarte
        $discard->update([
            'discard_status' => 'recovered',
            'discard_recovery_date' => now(),
            'discard_notes' => $validated['recovery_notes'] ?? null,
        ]);

        // Crear una nueva entrada en el stock (processed_bins) con el descarte recuperado
        $newTarja = ProcessedBin::create([
            'purchase_id' => null, // No viene de una compra
            'supplier_id' => null,
            'bin_id' => null,
            'tarja_number' => 'DESC-' . $discard->order_number . '-' . now()->format('YmdHis'),
            'gross_weight' => $discard->discard_kg,
            'net_fruit_weight' => $discard->discard_kg,
            'calibre' => $discard->output_caliber ?? 'mixto',
            'trash_level' => 4, // Asumimos buen estado
            'damage_percentage' => 0,
            'csg_code' => 'DESCARTE',
            'internal_supplier_code' => 'COFRUPA-RECUP',
            'lote' => 'RECUP-' . $discard->order_number,
            'stock_status' => 'available',
            'available_kg' => $discard->discard_kg,
            'assigned_kg' => 0,
            'used_kg' => 0,
            'location' => $validated['recovery_location'],
            'notes' => 'Descarte recuperado de orden de producción #' . $discard->order_number . '. ' . ($validated['recovery_notes'] ?? ''),
        ]);

        // Generar QR para la nueva tarja
        $newTarja->generateInitialQrCode();

        return redirect()->route('discards.index')
            ->with('success', "Descarte recuperado exitosamente. Nueva tarja: {$newTarja->tarja_number}");
    }

    public function dispose(Request $request, PlantProductionOrder $discard)
    {
        $validated = $request->validate([
            'dispose_reason' => 'required|string',
        ]);

        $discard->update([
            'discard_status' => 'disposed',
            'discard_recovery_date' => now(),
            'discard_notes' => 'Desechado: ' . $validated['dispose_reason'],
        ]);

        return redirect()->route('discards.index')
            ->with('success', 'Descarte marcado como desechado');
    }

    public function bulkRecover(Request $request)
    {
        $validated = $request->validate([
            'discard_ids' => 'required|array',
            'discard_ids.*' => 'exists:plant_production_orders,id',
            'recovery_location' => 'required|string|max:255',
            'recovery_notes' => 'nullable|string',
        ]);

        $discards = PlantProductionOrder::whereIn('id', $validated['discard_ids'])
            ->where('discard_status', 'pending')
            ->get();

        $totalRecovered = 0;
        $tarjas = [];

        foreach ($discards as $discard) {
            $discard->update([
                'discard_status' => 'recovered',
                'discard_recovery_date' => now(),
                'discard_notes' => $validated['recovery_notes'] ?? null,
            ]);

            $newTarja = ProcessedBin::create([
                'purchase_id' => null,
                'supplier_id' => null,
                'bin_id' => null,
                'tarja_number' => 'DESC-' . $discard->order_number . '-' . now()->format('YmdHis'),
                'gross_weight' => $discard->discard_kg,
                'net_fruit_weight' => $discard->discard_kg,
                'calibre' => $discard->output_caliber ?? 'mixto',
                'trash_level' => 4,
                'damage_percentage' => 0,
                'csg_code' => 'DESCARTE',
                'internal_supplier_code' => 'COFRUPA-RECUP',
                'lote' => 'RECUP-' . $discard->order_number,
                'stock_status' => 'available',
                'available_kg' => $discard->discard_kg,
                'assigned_kg' => 0,
                'used_kg' => 0,
                'location' => $validated['recovery_location'],
                'notes' => 'Descarte recuperado masivamente. Orden #' . $discard->order_number,
            ]);

            $newTarja->generateInitialQrCode();
            $totalRecovered += $discard->discard_kg;
            $tarjas[] = $newTarja->tarja_number;
        }

        return redirect()->route('discards.index')
            ->with('success', "Recuperados {$totalRecovered} kg en " . count($tarjas) . " tarjas: " . implode(', ', $tarjas));
    }
}
