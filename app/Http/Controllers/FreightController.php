<?php

namespace App\Http\Controllers;

use App\Models\Freight;
use App\Models\LogisticsCompany;
use App\Models\Purchase;
use App\Models\ProcessOrder;
use App\Models\Shipment;
use App\Models\PlantShipment;
use App\Models\SupplyPurchase;
use Illuminate\Http\Request;

class FreightController extends Controller
{
    public function index(Request $request)
    {
        $query = Freight::with(['logisticsCompany']);

        // Filtros
        if ($request->filled('freight_type')) {
            $query->where('freight_type', $request->freight_type);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->where('freight_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('freight_date', '<=', $request->date_to);
        }

        $freights = $query->latest('freight_date')->paginate(20)->withQueryString();

        // Estadísticas
        $totalPending = Freight::where('payment_status', 'pending')->sum('freight_cost');
        $totalPaid = Freight::where('payment_status', 'paid')->sum('freight_cost');
        $totalByType = Freight::selectRaw('freight_type, SUM(freight_cost) as total')
            ->groupBy('freight_type')
            ->get()
            ->pluck('total', 'freight_type');

        $stats = [
            'total_pending' => $totalPending,
            'total_paid' => $totalPaid,
            'by_type' => $totalByType,
        ];

        return view('ventas.fletes.index', compact('freights', 'stats'));
    }

    public function create()
    {
        $logisticsCompanies = LogisticsCompany::all();
        $purchases = Purchase::orderBy('created_at', 'desc')->limit(50)->get();
        $processOrders = ProcessOrder::orderBy('created_at', 'desc')->limit(50)->get();
        $shipments = Shipment::orderBy('created_at', 'desc')->limit(50)->get();
        $plantShipments = PlantShipment::orderBy('created_at', 'desc')->limit(50)->get();
        $supplyPurchases = SupplyPurchase::orderBy('created_at', 'desc')->limit(50)->get();

        return view('ventas.fletes.create', compact(
            'logisticsCompanies',
            'purchases',
            'processOrders',
            'shipments',
            'plantShipments',
            'supplyPurchases'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'freight_type' => 'required|in:reception,to_processing,to_port,supply_purchase,other',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'vehicle_plate' => 'nullable|string|max:50',
            'logistics_company_id' => 'nullable|exists:logistics_companies,id',
            'freight_cost' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid',
            'freight_date' => 'required|date',
            'kilos' => 'nullable|numeric|min:0',
            'guide_number' => 'nullable|string|max:100',
            'purchase_id' => 'nullable|exists:purchases,id',
            'process_order_id' => 'nullable|exists:process_orders,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'plant_shipment_id' => 'nullable|exists:plant_shipments,id',
            'supply_purchase_id' => 'nullable|exists:supply_purchases,id',
            'notes' => 'nullable|string',
        ]);

        Freight::create($validated);

        return redirect()->route('ventas.fletes.index')
            ->with('success', 'Flete registrado exitosamente');
    }

    public function show(Freight $freight)
    {
        $freight->load(['logisticsCompany', 'purchase', 'processOrder', 'shipment', 'plantShipment', 'supplyPurchase']);
        return view('ventas.fletes.show', compact('freight'));
    }

    public function edit(Freight $freight)
    {
        $logisticsCompanies = LogisticsCompany::all();
        $purchases = Purchase::orderBy('created_at', 'desc')->limit(50)->get();
        $processOrders = ProcessOrder::orderBy('created_at', 'desc')->limit(50)->get();
        $shipments = Shipment::orderBy('created_at', 'desc')->limit(50)->get();
        $plantShipments = PlantShipment::orderBy('created_at', 'desc')->limit(50)->get();
        $supplyPurchases = SupplyPurchase::orderBy('created_at', 'desc')->limit(50)->get();

        return view('ventas.fletes.edit', compact(
            'freight',
            'logisticsCompanies',
            'purchases',
            'processOrders',
            'shipments',
            'plantShipments',
            'supplyPurchases'
        ));
    }

    public function update(Request $request, Freight $freight)
    {
        $validated = $request->validate([
            'freight_type' => 'required|in:reception,to_processing,to_port,supply_purchase,other',
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'driver_name' => 'nullable|string|max:255',
            'vehicle_plate' => 'nullable|string|max:50',
            'logistics_company_id' => 'nullable|exists:logistics_companies,id',
            'freight_cost' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid',
            'freight_date' => 'required|date',
            'kilos' => 'nullable|numeric|min:0',
            'guide_number' => 'nullable|string|max:100',
            'purchase_id' => 'nullable|exists:purchases,id',
            'process_order_id' => 'nullable|exists:process_orders,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'plant_shipment_id' => 'nullable|exists:plant_shipments,id',
            'supply_purchase_id' => 'nullable|exists:supply_purchases,id',
            'notes' => 'nullable|string',
        ]);

        $freight->update($validated);

        return redirect()->route('ventas.fletes.index')
            ->with('success', 'Flete actualizado exitosamente');
    }

    public function destroy(Freight $freight)
    {
        $freight->delete();
        return redirect()->route('ventas.fletes.index')
            ->with('success', 'Flete eliminado exitosamente');
    }
}
