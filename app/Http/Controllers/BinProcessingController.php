<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\ProcessedBin;
use App\Models\Supplier;
use App\Models\Bin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BinProcessingController extends Controller
{
    /**
     * Display a listing of processed bins.
     */
    public function index()
    {
        $processedBins = ProcessedBin::with(['supplier'])
            ->where('status', 'processed')
            ->orderBy('processed_at', 'desc')
            ->paginate(15);

        return view('bin_processing.index', compact('processedBins'));
    }

    /**
     * Show the form for processing bins (mixing from different suppliers).
     */
    public function create(Request $request)
    {
        // Get all received bins that can be processed
        $availableBins = ProcessedBin::with('supplier')
            ->whereIn('status', ['received', 'processed'])
            ->orderBy('supplier_id')
            ->orderBy('current_bin_number')
            ->get()
            ->groupBy('supplier.name');

        $purchase = null;
        if ($request->has('purchase_id')) {
            $purchase = Purchase::find($request->purchase_id);
        }

        return view('bin_processing.create', compact('availableBins', 'purchase'));
    }

    /**
     * Store processed bin from mixing multiple source bins.
     */
    public function store(Request $request)
    {
        $request->validate([
            'source_bin_ids' => 'required|array|min:1',
            'source_bin_ids.*' => 'exists:processed_bins,id',
            'new_bin_number' => 'required|string|max:50|unique:processed_bins,current_bin_number',
            'processed_calibre' => [
                'required',
                Rule::in([
                    '80-90', '120-x', '90-100', '70-90',
                    'Grande 50-60', 'Mediana 40-50', 'Pequeña 30-40'
                ])
            ],
            'notes' => 'nullable|string|max:500',
        ]);

        // Get all source bins
        $sourceBins = ProcessedBin::whereIn('id', $request->source_bin_ids)->get();

        if ($sourceBins->isEmpty()) {
            return back()->with('error', 'No se encontraron los bins seleccionados.');
        }

        // Calculate total weight from all source bins
        $totalWeight = $sourceBins->sum('current_weight');

        // Create new processed bin (this represents the mixed result)
        $processedBin = ProcessedBin::create([
            'supplier_id' => $sourceBins->first()->supplier_id, // Primary supplier
            'entry_date' => now(), // Processing date becomes entry for new bin
            'original_bin_number' => $request->new_bin_number,
            'current_bin_number' => $request->new_bin_number,
            'original_weight' => $totalWeight,
            'processed_weight' => $totalWeight,
            'original_calibre' => $request->processed_calibre,
            'processed_calibre' => $request->processed_calibre,
            'status' => 'processed',
            'received_at' => now(),
            'processing_date' => now(),
            'processed_at' => now(),
            'processing_history' => [
                [
                    'date' => now()->format('Y-m-d H:i:s'),
                    'action' => 'created_from_mixing',
                    'source_bins' => $sourceBins->pluck('current_bin_number')->toArray(),
                    'total_weight' => $totalWeight,
                    'calibre' => $request->processed_calibre,
                ]
            ],
            'notes' => $request->notes,
        ]);

        // Generate QR code for the new processed bin
        $processedBin->generateInitialQrCode();

        // Update source bins to mark them as used in processing
        foreach ($sourceBins as $sourceBin) {
            $history = $sourceBin->processing_history ?? [];
            $history[] = [
                'date' => now()->format('Y-m-d H:i:s'),
                'action' => 'used_in_mixing',
                'target_bin' => $request->new_bin_number,
                'weight_used' => $sourceBin->current_weight,
            ];

            $sourceBin->update([
                'processing_history' => $history,
                'status' => 'processed', // Mark as processed/used
            ]);
        }

        return redirect()->route('bin_processing.index')
            ->with('success', 'Bin procesado creado exitosamente. ' . count($sourceBins) . ' bins fuente utilizados.');
    }

    /**
     * Display the specified processed bin.
     */
    public function show($id)
    {
        $processedBin = ProcessedBin::with(['purchase', 'supplier'])->findOrFail($id);

        return view('processed_bins.show', compact('processedBin'));
    }

    /**
     * Update processed bin status (for shipping/delivery).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'exit_date' => 'nullable|date',
            'destination' => 'nullable|string|max:255',
            'guide_number' => 'nullable|string|max:100',
            'status' => 'required|in:processed,shipped,delivered',
        ]);

        $processedBin = ProcessedBin::findOrFail($id);
        $processedBin->update($request->only(['exit_date', 'destination', 'guide_number', 'status']));

        return redirect()->back()->with('success', 'Bin actualizado exitosamente.');
    }

    /**
     * Scan QR code and deduct from inventory.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        try {
            $decryptedData = ProcessedBin::decryptQrData($request->qr_data);

            if (!$decryptedData || !ProcessedBin::validateQrData($decryptedData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código QR inválido o no pertenece a este sistema.'
                ], 400);
            }

            $processedBin = ProcessedBin::find($decryptedData['id']);

            if (!$processedBin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bin no encontrado.'
                ], 404);
            }

            if ($processedBin->status === 'delivered') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este bin ya fue entregado anteriormente.'
                ], 400);
            }

            // Update status to delivered (deduct from inventory)
            $processedBin->update([
                'status' => 'delivered',
                'exit_date' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bin entregado exitosamente. Inventario actualizado.',
                'data' => [
                    'bin_number' => $processedBin->current_bin_number,
                    'weight' => $processedBin->current_weight,
                    'supplier' => $processedBin->supplier->name,
                    'status' => $processedBin->status_display,
                    'delivered_at' => now()->format('Y-m-d H:i:s'),
                    'qr_version' => $decryptedData['version'] ?? 'N/A',
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el código QR.'
            ], 500);
        }
    }
}
