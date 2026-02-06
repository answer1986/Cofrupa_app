<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\ProcessedBin;
use App\Models\Supplier;
use App\Models\Client;
use App\Models\Bin;
use App\Models\BinTraceability;
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

        $suppliers = Supplier::orderBy('name')->get();

        return view('bin_processing.create', compact('availableBins', 'purchase', 'suppliers'));
    }

    /**
     * Store processed bins (bin por bin: un ProcessedBin por cada fila de la tabla).
     */
    public function store(Request $request)
    {
        $request->validate([
            'bins' => 'required|array|min:1',
            'bins.*.source_bin_id' => 'required|exists:processed_bins,id',
            'bins.*.new_bin_number' => 'required|string|max:50',
            'bins.*.numero_tarja' => 'nullable|string|max:100',
            'bins.*.net_weight' => 'nullable|numeric|min:0',
            'bins.*.cofrupa_plastic_bins_count' => 'nullable|integer|min:0',
            'bins.*.numero_lote' => 'nullable|string|max:100',
            'bins.*.dano_total' => 'nullable|numeric|min:0|max:100',
            'bins.*.processed_calibre' => [
                'required',
                Rule::in([
                    '80-90', '120-x', '90-100', '70-90',
                    'Grande 50-60', 'Mediana 40-50', 'Pequeña 30-40'
                ])
            ],
            'bins.*.calibre_promedio' => 'nullable|string|max:50',
            'bins.*.observations' => 'nullable|string|max:2000',
            'supplier_id' => 'required|exists:suppliers,id',
            'processing_start_date' => 'required|date',
            'processing_end_date' => 'nullable|date|after_or_equal:processing_start_date',
            'bins_processed_per_day' => 'nullable|string',
            'fruit_type' => 'nullable|string|max:100',
            'csg_code' => 'nullable|string|max:100',
            'external_service' => 'nullable|boolean',
            'external_service_client' => 'nullable|string|max:255|required_if:external_service,1',
            'external_service_client_id' => 'nullable|exists:clients,id',
            'external_service_period_start' => 'nullable|date|required_if:external_service,1',
            'external_service_period_end' => 'nullable|date|after_or_equal:external_service_period_start|required_if:external_service,1',
        ]);

        $binsInput = $request->bins;
        $newBinNumbers = collect($binsInput)->pluck('new_bin_number')->toArray();
        if (count($newBinNumbers) !== count(array_unique($newBinNumbers))) {
            return back()->withInput()->with('error', 'Los números de bin resultante deben ser únicos.');
        }
        foreach ($newBinNumbers as $num) {
            if (ProcessedBin::where('current_bin_number', $num)->exists()) {
                return back()->withInput()->with('error', "El número de bin '{$num}' ya existe.");
            }
        }

        $binsPerDay = [];
        if ($request->bins_processed_per_day) {
            $binsPerDay = json_decode($request->bins_processed_per_day, true) ?? [];
        }

        $externalService = $request->has('external_service') && $request->external_service == '1';
        $externalClientName = $request->external_service_client;
        if ($request->external_service_client_id) {
            $client = Client::find($request->external_service_client_id);
            $externalClientName = $client ? $client->name : $request->external_service_client;
        }

        $created = 0;
        foreach ($binsInput as $row) {
            $sourceBin = ProcessedBin::find($row['source_bin_id']);
            if (!$sourceBin) {
                continue;
            }

            $netWeight = isset($row['net_weight']) && $row['net_weight'] !== '' ? (float) $row['net_weight'] : $sourceBin->current_weight;

            $processedBin = ProcessedBin::create([
                'supplier_id' => $request->supplier_id,
                'entry_date' => $request->processing_start_date,
                'original_bin_number' => $row['new_bin_number'],
                'current_bin_number' => $row['new_bin_number'],
                'tarja_number' => $row['numero_tarja'] ?? null,
                'original_weight' => $netWeight,
                'processed_weight' => $netWeight,
                'net_fruit_weight' => $netWeight,
                'original_calibre' => $row['processed_calibre'],
                'processed_calibre' => $row['processed_calibre'],
                'unidades_per_pound_avg' => $row['calibre_promedio'] ?? null,
                'damage_percentage' => $row['dano_total'] ?? null,
                'lote' => $row['numero_lote'] ?? null,
                'processing_start_date' => $request->processing_start_date,
                'processing_end_date' => $request->processing_end_date,
                'bins_processed_per_day' => $binsPerDay,
                'observations' => $row['observations'] ?? null,
                'fruit_type' => $request->fruit_type,
                'csg_code' => $request->csg_code,
                'cofrupa_plastic_bins_count' => (int) ($row['cofrupa_plastic_bins_count'] ?? 0),
                'external_service' => $externalService,
                'external_service_client' => $externalClientName,
                'external_service_client_id' => $request->external_service_client_id,
                'external_service_period_start' => $request->external_service_period_start,
                'external_service_period_end' => $request->external_service_period_end,
                'status' => 'processed',
                'received_at' => now(),
                'processing_date' => $request->processing_start_date,
                'processed_at' => $request->processing_end_date ?? now(),
                'processing_history' => [
                    [
                        'date' => now()->format('Y-m-d H:i:s'),
                        'action' => 'created_from_mixing',
                        'source_bins' => [$sourceBin->current_bin_number],
                        'total_weight' => $netWeight,
                        'calibre' => $row['processed_calibre'],
                    ]
                ],
                'notes' => $row['notes'] ?? null,
            ]);

            $processedBin->generateInitialQrCode();
            $created++;

            $history = $sourceBin->processing_history ?? [];
            $history[] = [
                'date' => now()->format('Y-m-d H:i:s'),
                'action' => 'used_in_mixing',
                'target_bin' => $row['new_bin_number'],
                'weight_used' => $sourceBin->current_weight,
            ];
            $sourceBin->update([
                'processing_history' => $history,
                'status' => 'processed',
            ]);

            BinTraceability::record(BinTraceability::OPERATION_MIXING, [
                'source_bin_id' => $sourceBin->id,
                'target_bin_id' => $processedBin->id,
                'weight_kg' => $sourceBin->current_weight,
                'operation_date' => now(),
                'notes' => "Procesado en bin {$row['new_bin_number']}",
            ]);
        }

        return redirect()->route('bin_processing.index')
            ->with('success', 'Procesamiento guardado: ' . $created . ' bin(s) creado(s).');
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
     * Mostrar trazabilidad de un bin procesado
     */
    public function traceability($id)
    {
        $processedBin = ProcessedBin::with(['supplier', 'purchase'])->findOrFail($id);
        $traceabilityInfo = $processedBin->getTraceabilityInfo();

        return view('bin_processing.traceability', compact('processedBin', 'traceabilityInfo'));
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

    /**
     * Crear cliente externo rápido desde procesamiento (solo con nombre).
     */
    public function quickCreateExternalClient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $client = Client::create([
            'name' => $request->name,
            'type' => 'new',
            'is_incomplete' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cliente creado exitosamente',
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
            ]
        ]);
    }
}
