<?php

namespace App\Http\Controllers;

use App\Models\PlantShipment;
use App\Models\Plant;
use App\Models\ProcessOrder;
use App\Models\ProcessedBin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlantShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = PlantShipment::with(['plant', 'processOrder']);

        // Filtros
        if ($request->filled('plant_id')) {
            $query->where('plant_id', $request->plant_id);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->where('shipment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('shipment_date', '<=', $request->date_to);
        }

        $shipments = $query->latest('shipment_date')->paginate(15);
        $plants = Plant::where('is_active', true)->get();

        return view('plant_shipments.index', compact('shipments', 'plants'));
    }

    public function create()
    {
        $plants = Plant::where('is_active', true)->get();
        $processOrders = ProcessOrder::with(['plant', 'supplier'])->orderBy('created_at', 'desc')->get();
        
        // Obtener bins procesados disponibles (con stock disponible)
        $availableBins = ProcessedBin::where('stock_status', 'available')
            ->where('available_kg', '>', 0)
            ->orderBy('processing_date', 'desc')
            ->get();

        return view('plant_shipments.create', compact('plants', 'processOrders', 'availableBins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plant_id' => 'required|exists:plants,id',
            'process_order_id' => 'nullable|exists:process_orders,id',
            'driver_name' => 'required|string|max:255',
            'vehicle_plate' => 'required|string|max:50',
            'guide_number' => 'required|string|max:100|unique:plant_shipments,guide_number',
            'shipment_date' => 'required|date',
            'destination' => 'required|string|max:255',
            'bin_type' => 'nullable|string|max:100',
            'shipment_cost' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid',
            'notes' => 'nullable|string',
            'bins' => 'required|array|min:1',
            'bins.*.id' => 'required|exists:processed_bins,id',
            'bins.*.kilos' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // Calcular total de kilos
            $totalKilos = collect($validated['bins'])->sum('kilos');

            // Crear el despacho
            $shipment = PlantShipment::create([
                'plant_id' => $validated['plant_id'],
                'process_order_id' => $validated['process_order_id'] ?? null,
                'driver_name' => $validated['driver_name'],
                'vehicle_plate' => $validated['vehicle_plate'],
                'guide_number' => $validated['guide_number'],
                'shipment_date' => $validated['shipment_date'],
                'destination' => $validated['destination'],
                'total_kilos' => $totalKilos,
                'bin_type' => $validated['bin_type'],
                'shipment_cost' => $validated['shipment_cost'],
                'payment_status' => $validated['payment_status'],
                'notes' => $validated['notes'],
            ]);

            // Asociar bins y rebajar stock
            foreach ($validated['bins'] as $binData) {
                $processedBin = ProcessedBin::findOrFail($binData['id']);
                $kilosSent = $binData['kilos'];

                // Verificar que hay stock suficiente
                if ($processedBin->available_kg < $kilosSent) {
                    throw new \Exception("El bin {$processedBin->current_bin_number} no tiene suficiente stock disponible.");
                }

                // Asociar bin al despacho
                $shipment->bins()->attach($processedBin->id, [
                    'kilos_sent' => $kilosSent
                ]);

                // Rebajar stock del bin
                $processedBin->used_kg += $kilosSent;
                $processedBin->available_kg -= $kilosSent;
                
                // Actualizar estado si se agotó el stock
                if ($processedBin->available_kg <= 0) {
                    $processedBin->stock_status = 'depleted';
                }
                
                $processedBin->save();
            }

            DB::commit();

            return redirect()->route('plant-shipments.index')
                ->with('success', 'Despacho a planta creado exitosamente. Guía: ' . $shipment->guide_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Error al crear el despacho: ' . $e->getMessage()]);
        }
    }

    public function show(PlantShipment $plantShipment)
    {
        $plantShipment->load(['plant', 'processOrder', 'bins']);
        return view('plant_shipments.show', compact('plantShipment'));
    }

    public function edit(PlantShipment $plantShipment)
    {
        $plants = Plant::where('is_active', true)->get();
        $processOrders = ProcessOrder::with(['plant', 'supplier'])->orderBy('created_at', 'desc')->get();
        $plantShipment->load(['bins']);

        return view('plant_shipments.edit', compact('plantShipment', 'plants', 'processOrders'));
    }

    public function update(Request $request, PlantShipment $plantShipment)
    {
        $validated = $request->validate([
            'driver_name' => 'required|string|max:255',
            'vehicle_plate' => 'required|string|max:50',
            'shipment_date' => 'required|date',
            'destination' => 'required|string|max:255',
            'shipment_cost' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid',
            'notes' => 'nullable|string',
        ]);

        $plantShipment->update($validated);

        return redirect()->route('plant-shipments.index')
            ->with('success', 'Despacho actualizado exitosamente');
    }

    public function destroy(PlantShipment $plantShipment)
    {
        DB::beginTransaction();
        try {
            // Devolver stock a los bins
            foreach ($plantShipment->bins as $bin) {
                $kilosSent = $bin->pivot->kilos_sent;
                $bin->used_kg -= $kilosSent;
                $bin->available_kg += $kilosSent;
                $bin->stock_status = 'available';
                $bin->save();
            }

            $plantShipment->delete();

            DB::commit();

            return redirect()->route('plant-shipments.index')
                ->with('success', 'Despacho eliminado y stock devuelto exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar el despacho: ' . $e->getMessage()]);
        }
    }
}
