<?php

namespace App\Http\Controllers;

use App\Models\ProcessOrder;
use App\Models\Plant;
use Illuminate\Http\Request;

class CupoRequestController extends Controller
{
    /**
     * Petición de cupos: ver qué tarjas se rebajaron, kilos enviados vs devueltos,
     * rendimiento, camión/patente, planta, fecha, horario, orden.
     */
    public function index(Request $request)
    {
        $query = ProcessOrder::with(['plant', 'supplier', 'tarjas'])
            ->whereIn('status', ['pending', 'in_progress', 'completed']);

        if ($request->filled('plant_id')) {
            $query->where('plant_id', $request->plant_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('low_performance')) {
            $query->whereNotNull('kilos_sent')
                ->where('kilos_sent', '>', 0)
                ->where(function ($q) {
                    $q->whereNull('kilos_produced')
                        ->orWhereRaw('(kilos_produced / NULLIF(kilos_sent, 0)) < 0.7');
                });
        }

        if ($request->filled('date_from')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_date', '>=', $request->date_from)
                    ->orWhere('shipment_date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_date', '<=', $request->date_to)
                    ->orWhere('shipment_date', '<=', $request->date_to);
            });
        }

        $orders = $query->latest('order_date')->latest('shipment_date')->paginate(20)->withQueryString();

        $plants = Plant::where('is_active', true)->orderBy('name')->get();

        return view('processing.request.index', compact('orders', 'plants'));
    }
}
