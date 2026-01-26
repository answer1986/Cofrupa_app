<?php

namespace App\Http\Controllers;

use App\Models\ProcessedBin;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = ProcessedBin::with(['supplier'])
            ->whereNotNull('tarja_number'); // Solo tarjas generadas

        // Filtro por estado de stock
        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        // Filtro por proveedor
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filtro por calibre
        if ($request->filled('caliber')) {
            $query->where(function($q) use ($request) {
                $q->where('original_calibre', 'like', '%' . $request->caliber . '%')
                  ->orWhere('processed_calibre', 'like', '%' . $request->caliber . '%');
            });
        }

        // Filtro por lote
        if ($request->filled('lote')) {
            $query->where('lote', 'like', '%' . $request->lote . '%');
        }

        // Filtro por ubicación
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Ordenar por más reciente
        $tarjas = $query->latest('created_at')->paginate(20);

        // Estadísticas de stock
        $stats = [
            'total_tarjas' => ProcessedBin::whereNotNull('tarja_number')->count(),
            'available' => ProcessedBin::where('stock_status', 'available')->count(),
            'assigned' => ProcessedBin::where('stock_status', 'assigned')->count(),
            'in_process' => ProcessedBin::where('stock_status', 'in_process')->count(),
            'completed' => ProcessedBin::where('stock_status', 'completed')->count(),
            'total_kg_available' => ProcessedBin::where('stock_status', 'available')->sum('available_kg'),
            'total_kg_assigned' => ProcessedBin::sum('assigned_kg'),
        ];

        $suppliers = Supplier::all();

        return view('stock.index', compact('tarjas', 'stats', 'suppliers'));
    }

    public function show($id)
    {
        $tarja = ProcessedBin::with(['supplier', 'orders'])->findOrFail($id);
        
        return view('stock.show', compact('tarja'));
    }

    public function updateLocation(Request $request, $id)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:255',
        ]);

        $tarja = ProcessedBin::findOrFail($id);
        $tarja->update(['location' => $validated['location']]);

        return redirect()->back()->with('success', 'Ubicación actualizada exitosamente');
    }
}
