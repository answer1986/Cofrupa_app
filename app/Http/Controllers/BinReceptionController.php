<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\ProcessedBin;
use App\Models\BinTraceability;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BinReceptionController extends Controller
{
    /**
     * Display a listing of received bins.
     */
    public function index()
    {
        $receivedBins = ProcessedBin::with(['supplier', 'purchase'])
            ->whereIn('status', ['received', 'processed'])
            ->orderBy('received_at', 'desc')
            ->paginate(15);

        return view('bin_reception.index', compact('receivedBins'));
    }

    /**
     * Show the form for receiving bins.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('bin_reception.create', compact('suppliers'));
    }

    /**
     * Store received bins.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'entry_date' => 'required|date',
            'vehicle_plate' => 'required|string|max:20',
            'reception_weight_per_truck' => 'nullable|numeric|min:0',
            'reception_calibre' => [
                'required',
                Rule::in([
                    '80-90', '120-x', '90-100', '70-90',
                    'Grande 50-60', 'Mediana 40-50', 'Pequeña 30-40'
                ])
            ],
            'reception_unidades_per_pound_avg' => 'nullable|numeric|min:0',
            'guide_number' => 'nullable|string|max:100',
            'existing_bins' => 'nullable|array',
            'existing_bins.*.bin_ids' => 'required_with:existing_bins|array|min:1|max:5',
            'existing_bins.*.bin_ids.*' => 'exists:bins,id',
            'existing_bins.*.gross_weight' => 'required_with:existing_bins|numeric|min:0',
            'existing_bins.*.wood_bins_count' => 'required_with:existing_bins|integer|min:0',
            'existing_bins.*.plastic_bins_count' => 'required_with:existing_bins|integer|min:0',
            'existing_bins.*.net_fruit_weight' => 'nullable|numeric|min:0',
            'existing_bins.*.trash_level' => 'required_with:existing_bins|in:alto,mediano,bajo,limpio',
            'existing_bins.*.humidity' => 'nullable|numeric|min:0|max:100',
            'existing_bins.*.damage_percentage' => 'nullable|numeric|min:0|max:100',
            'existing_bins.*.notes' => 'nullable|string|max:500',
            'lote' => 'nullable|string|max:100',
            'bins' => 'nullable|array',
            'bins.*.bin_number' => 'nullable|string|max:255',
            'bins.*.gross_weight' => 'required_with:bins|numeric|min:0',
            'bins.*.wood_bins_count' => 'required_with:bins|integer|min:0',
            'bins.*.plastic_bins_count' => 'required_with:bins|integer|min:0',
            'bins.*.net_fruit_weight' => 'nullable|numeric|min:0',
            'bins.*.trash_level' => 'required_with:bins|in:alto,mediano,bajo,limpio',
            'bins.*.humidity' => 'nullable|numeric|min:0|max:100',
            'bins.*.damage_percentage' => 'nullable|numeric|min:0|max:100',
            'bins.*.notes' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        // Ensure at least one type of bins is provided
        if (empty($request->existing_bins) && empty($request->bins)) {
            return back()->withErrors(['general' => 'Debe crear al menos un grupo de pesaje (bins existentes o bins nuevos).']);
        }

        $supplier = Supplier::findOrFail($request->supplier_id);
        $receivedBins = [];
        $batchId = 'REC-' . now()->format('YmdHis') . '-' . $supplier->id;
        
        // Calculate totals from all groups
        $totalNetWeight = 0;
        $totalGrossWeight = 0;
        $binsCount = 0;
        
        // Calculate from existing bins groups
        if ($request->has('existing_bins') && !empty($request->existing_bins)) {
            foreach ($request->existing_bins as $groupData) {
                $grossWeight = $groupData['gross_weight'] ?? 0;
                $woodBins = $groupData['wood_bins_count'] ?? 0;
                $plasticBins = $groupData['plastic_bins_count'] ?? 0;
                $binsInGroup = $woodBins + $plasticBins;
                
                $woodBinWeight = $woodBins * \App\Models\Bin::getEmptyBinWeight('wood');
                $plasticBinWeight = $plasticBins * \App\Models\Bin::getEmptyBinWeight('plastic');
                $netWeight = max(0, $grossWeight - $woodBinWeight - $plasticBinWeight);
                
                $totalGrossWeight += $grossWeight;
                $totalNetWeight += $netWeight;
                $binsCount += $binsInGroup;
            }
        }
        
        // Calculate from new bins groups
        if ($request->has('bins') && !empty($request->bins)) {
            foreach ($request->bins as $binData) {
                $grossWeight = $binData['gross_weight'] ?? 0;
                $woodBins = $binData['wood_bins_count'] ?? 0;
                $plasticBins = $binData['plastic_bins_count'] ?? 0;
                $binsInGroup = $woodBins + $plasticBins;
                
                $woodBinWeight = $woodBins * \App\Models\Bin::getEmptyBinWeight('wood');
                $plasticBinWeight = $plasticBins * \App\Models\Bin::getEmptyBinWeight('plastic');
                $netWeight = max(0, $grossWeight - $woodBinWeight - $plasticBinWeight);
                
                $totalGrossWeight += $grossWeight;
                $totalNetWeight += $netWeight;
                $binsCount += $binsInGroup;
            }
        }

        // Handle existing bins groups (bins that were previously assigned to supplier)
        if ($request->has('existing_bins') && !empty($request->existing_bins)) {
            foreach ($request->existing_bins as $groupData) {
                $binIds = $groupData['bin_ids'] ?? [];
                if (empty($binIds)) continue;
                
                $existingBins = \App\Models\Bin::whereIn('id', $binIds)
                                               ->where('supplier_id', $request->supplier_id)
                                               ->get();
                
                if ($existingBins->isEmpty()) continue;
                
                $grossWeight = $groupData['gross_weight'] ?? 0;
                $woodBins = $groupData['wood_bins_count'] ?? 0;
                $plasticBins = $groupData['plastic_bins_count'] ?? 0;
                $binsInGroup = $woodBins + $plasticBins;
                
                // Calculate net weight: restar el peso de los bins (60kg madera, 45kg plástico)
                $woodBinWeight = $woodBins * 60; // Bin de madera pesa 60kg
                $plasticBinWeight = $plasticBins * 45; // Bin de plástico pesa 45kg
                $netWeight = max(0, $grossWeight - $woodBinWeight - $plasticBinWeight);
                
                // Determine bin type (use the majority or first)
                $binType = $woodBins >= $plasticBins ? 'wood' : 'plastic';
                
                // Create bin numbers string
                $binNumbers = $existingBins->pluck('bin_number')->join(', ');
                
                // Create processed bin record for the group
                $processedBin = ProcessedBin::create([
                    'supplier_id' => $supplier->id,
                    'entry_date' => $request->entry_date,
                    'vehicle_plate' => strtoupper($request->vehicle_plate),
                    'guide_number' => $request->guide_number,
                    'original_bin_number' => $binNumbers,
                    'bin_type' => $binType,
                    'trash_level' => $groupData['trash_level'] ?? 'mediano',
                    'current_bin_number' => $binNumbers,
                    'original_weight' => $netWeight,
                    'gross_weight' => $grossWeight,
                    'bins_in_group' => $binsInGroup,
                    'wood_bins_count' => $woodBins,
                    'plastic_bins_count' => $plasticBins,
                    'net_fruit_weight' => $netWeight,
                    'original_calibre' => $request->reception_calibre,
                    'reception_total_weight' => $totalNetWeight,
                    'reception_weight_per_truck' => $request->reception_weight_per_truck,
                    'reception_bins_count' => $binsCount,
                    'reception_batch_id' => $batchId,
                    'lote' => $request->lote,
                    'unidades_per_pound_avg' => $request->reception_unidades_per_pound_avg,
                    'humidity' => $groupData['humidity'] ?? null,
                    'damage_percentage' => $groupData['damage_percentage'] ?? null,
                    'status' => 'received',
                    'received_at' => now(),
                    'notes' => $groupData['notes'] ?? $request->notes,
                ]);

                // Generate tarja number if not set
                if (!$processedBin->tarja_number) {
                    $tarjaNumber = 'TARJA-' . now()->format('Ymd') . '-' . str_pad($processedBin->id, 5, '0', STR_PAD_LEFT);
                    $processedBin->update(['tarja_number' => $tarjaNumber]);
                }
                
                // Generate initial QR code
                $processedBin->generateInitialQrCode();

                // Registrar trazabilidad inicial (recepción)
                BinTraceability::record(BinTraceability::OPERATION_INITIAL, [
                    'target_bin_id' => $processedBin->id,
                    'purchase_id' => $processedBin->purchase_id,
                    'weight_kg' => $netWeight,
                    'operation_date' => now(),
                    'notes' => "Recepción inicial - Lote: {$request->lote}, Placa: {$request->vehicle_plate}",
                ]);

                // Update existing bins status to returned
                foreach ($existingBins as $existingBin) {
                    $existingBin->update([
                        'status' => 'available',
                        'supplier_id' => null,
                        'return_date' => now(),
                    ]);
                }

                $receivedBins[] = $processedBin;
            }
        }

        // Handle new bins groups (bins not in the system)
        if ($request->has('bins') && !empty($request->bins)) {
            foreach ($request->bins as $binData) {
                $binNumber = $binData['bin_number'] ?? 'GRUPO-' . time() . '-' . rand(1000, 9999);
                
                // Check if bin number already exists in processed_bins
                $existingProcessedBin = ProcessedBin::where('original_bin_number', $binNumber)
                                                   ->where('supplier_id', $request->supplier_id)
                                                   ->first();

                if ($existingProcessedBin) {
                    continue; // Skip if already exists
                }

                $grossWeight = $binData['gross_weight'] ?? 0;
                $woodBins = $binData['wood_bins_count'] ?? 0;
                $plasticBins = $binData['plastic_bins_count'] ?? 0;
                $binsInGroup = $woodBins + $plasticBins;
                
                // Calculate net weight: restar el peso de los bins (60kg madera, 45kg plástico)
                $woodBinWeight = $woodBins * 60; // Bin de madera pesa 60kg
                $plasticBinWeight = $plasticBins * 45; // Bin de plástico pesa 45kg
                $netWeight = max(0, $grossWeight - $woodBinWeight - $plasticBinWeight);
                
                // Determine bin type (use the majority or first)
                $binType = $woodBins >= $plasticBins ? 'wood' : 'plastic';
                
                $bin = ProcessedBin::create([
                    'supplier_id' => $supplier->id,
                    'entry_date' => $request->entry_date,
                    'vehicle_plate' => strtoupper($request->vehicle_plate),
                    'guide_number' => $request->guide_number,
                    'original_bin_number' => $binNumber,
                    'bin_type' => $binType,
                    'trash_level' => $binData['trash_level'],
                    'current_bin_number' => $binNumber,
                    'original_weight' => $netWeight, // Store net weight as original_weight
                    'gross_weight' => $grossWeight,
                    'bins_in_group' => $binsInGroup,
                    'wood_bins_count' => $woodBins,
                    'plastic_bins_count' => $plasticBins,
                    'net_fruit_weight' => $netWeight,
                    'original_calibre' => $request->reception_calibre,
                    'reception_total_weight' => $totalNetWeight,
                    'reception_weight_per_truck' => $request->reception_weight_per_truck,
                    'reception_bins_count' => $binsCount,
                    'reception_batch_id' => $batchId,
                    'lote' => $request->lote,
                    'unidades_per_pound_avg' => $request->reception_unidades_per_pound_avg,
                    'humidity' => $binData['humidity'] ?? null,
                    'damage_percentage' => $binData['damage_percentage'] ?? null,
                    'status' => 'received',
                    'received_at' => now(),
                    'notes' => $binData['notes'] ?? $request->notes,
                ]);

                // Generate tarja number if not set
                if (!$bin->tarja_number) {
                    $tarjaNumber = 'TARJA-' . now()->format('Ymd') . '-' . str_pad($bin->id, 5, '0', STR_PAD_LEFT);
                    $bin->update(['tarja_number' => $tarjaNumber]);
                }
                
                // Generate initial QR code
                $bin->generateInitialQrCode();

                // Registrar trazabilidad inicial (recepción)
                BinTraceability::record(BinTraceability::OPERATION_INITIAL, [
                    'target_bin_id' => $bin->id,
                    'purchase_id' => $bin->purchase_id,
                    'weight_kg' => $netWeight,
                    'operation_date' => now(),
                    'notes' => "Recepción inicial - Lote: {$request->lote}, Placa: {$request->vehicle_plate}",
                ]);

                $receivedBins[] = $bin;
            }
        }

        if (empty($receivedBins)) {
            return back()->with('error', 'No se recibieron bins. Verifica que hayas seleccionado bins existentes o agregado bins nuevos.');
        }

        // If only one bin was received, redirect to its tarja
        if (count($receivedBins) === 1) {
            return redirect()->route('tarjas.show', $receivedBins[0]->id)
                ->with('success', 'Bin recibido exitosamente. Tarja generada.');
        }

        return redirect()->route('bin_reception.index')
            ->with('success', count($receivedBins) . ' bins recibidos exitosamente. Las tarjas han sido generadas.');
    }

    /**
     * Display the specified received bin.
     */
    public function show($id)
    {
        $bin = ProcessedBin::with(['supplier', 'purchase'])->findOrFail($id);

        return view('bin_reception.show', compact('bin'));
    }

    /**
     * Get bins for a specific supplier (API endpoint).
     */
    public function getSupplierBins($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);

        // Get bins that are assigned to this supplier and are in use
        $bins = $supplier->bins()
            ->where('status', 'in_use')
            ->orderBy('bin_number')
            ->get()
            ->map(function ($bin) {
                return [
                    'id' => $bin->id,
                    'bin_number' => $bin->bin_number,
                    'type' => $bin->type,
                    'type_display' => $bin->type_display,
                    'weight_capacity' => $bin->weight_capacity, // Peso del bin vacío (tara)
                    'status' => $bin->status,
                    'status_display' => $bin->status_display,
                    'delivery_date' => $bin->delivery_date ? $bin->delivery_date->format('Y-m-d') : null,
                ];
            });

        return response()->json(['bins' => $bins]);
    }

    /**
     * Get delivered bins for a specific supplier (API endpoint).
     */
    public function getDeliveredBins($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);

        // Get bins that were delivered to this supplier through BinAssignment
        $assignments = \App\Models\BinAssignment::where('supplier_id', $supplierId)
            ->with('bin')
            ->orderBy('delivery_date', 'desc')
            ->get()
            ->map(function ($assignment) {
                return [
                    'bin_number' => $assignment->bin->bin_number ?? 'N/A',
                    'delivery_date' => $assignment->delivery_date->format('d/m/Y'),
                    'return_date' => $assignment->return_date ? $assignment->return_date->format('d/m/Y') : null,
                ];
            });

        return response()->json(['bins' => $assignments]);
    }

    /**
     * Crear proveedor rápido desde recepción (solo con nombre)
     */
    public function quickCreateSupplier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Crear proveedor solo con nombre, marcado como incompleto (location null para que cuente en notificaciones)
        $supplier = Supplier::create([
            'name' => $request->name,
            'location' => null, // Sin completar, así aparece en "Proveedores por completar" de la campana
            'is_incomplete' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Proveedor creado exitosamente',
            'supplier' => [
                'id' => $supplier->id,
                'name' => $supplier->name,
            ]
        ]);
    }
}
