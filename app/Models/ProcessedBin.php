<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ProcessedBin extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'supplier_id',
        'entry_date',
        'vehicle_plate',
        'processing_date',
        'exit_date',
        'destination',
        'guide_number',
        'original_bin_number',
        'bin_type',
        'trash_level',
        'reception_total_weight',
        'reception_weight_per_truck',
        'reception_bins_count',
        'reception_batch_id',
        'tarja_number',
        'lote',
        'unidades_per_pound_avg',
        'humidity',
        'damage_percentage',
        'current_bin_number',
        'original_weight',
        'gross_weight',
        'bins_in_group',
        'wood_bins_count',
        'plastic_bins_count',
        'net_fruit_weight',
        'processed_weight',
        'original_calibre',
        'processed_calibre',
        'qr_code',
        'qr_generated_at',
        'qr_updated_at',
        'status',
        'received_at',
        'processed_at',
        'processing_history',
        'notes',
        'stock_status',
        'available_kg',
        'assigned_kg',
        'used_kg',
        'location',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'processing_date' => 'date',
        'exit_date' => 'date',
        'original_weight' => 'decimal:2',
        'gross_weight' => 'decimal:2',
        'net_fruit_weight' => 'decimal:2',
        'processed_weight' => 'decimal:2',
        'reception_total_weight' => 'decimal:2',
        'reception_weight_per_truck' => 'decimal:2',
        'unidades_per_pound_avg' => 'decimal:2',
        'humidity' => 'decimal:2',
        'damage_percentage' => 'decimal:2',
        'qr_generated_at' => 'datetime',
        'qr_updated_at' => 'datetime',
        'received_at' => 'datetime',
        'processed_at' => 'datetime',
        'processing_history' => 'array',
    ];

    // Get trash level stars display
    public function getTrashLevelStarsAttribute()
    {
        $stars = [
            'limpio' => 4,
            'bajo' => 3,
            'mediano' => 2,
            'alto' => 1,
        ];
        
        return $stars[$this->trash_level] ?? 0;
    }

    // Get bin type display
    public function getBinTypeDisplayAttribute()
    {
        return $this->bin_type === 'wood' ? 'Madera' : 'Plástico';
    }

    // Get trash level display
    public function getTrashLevelDisplayAttribute()
    {
        return ucfirst($this->trash_level ?? 'No especificado');
    }

    // Relationships
    public function purchase()
    {
        return $this->belongsTo(Purchase::class)->withDefault();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function orders()
    {
        return $this->belongsToMany(ProcessOrder::class, 'order_tarjas', 'processed_bin_id', 'process_order_id')
            ->withPivot('quantity_kg')
            ->withTimestamps();
    }

    // Trazabilidad: bins que fueron fuente para crear este bin
    public function sourceTraceability()
    {
        return $this->hasMany(BinTraceability::class, 'target_bin_id');
    }

    // Trazabilidad: bins que fueron creados desde este bin
    public function targetTraceability()
    {
        return $this->hasMany(BinTraceability::class, 'source_bin_id');
    }

    // Obtener todos los bins fuente (hacia atrás en la cadena)
    public function getSourceBins()
    {
        $sourceBins = collect();
        $traceability = $this->sourceTraceability()->with('sourceBin')->get();
        
        foreach ($traceability as $trace) {
            if ($trace->sourceBin) {
                $sourceBins->push($trace->sourceBin);
                // Recursivamente obtener bins fuente de los bins fuente
                $sourceBins = $sourceBins->merge($trace->sourceBin->getSourceBins());
            }
        }
        
        return $sourceBins->unique('id');
    }

    // Obtener todos los bins destino (hacia adelante en la cadena)
    public function getTargetBins()
    {
        $targetBins = collect();
        $traceability = $this->targetTraceability()->with('targetBin')->get();
        
        foreach ($traceability as $trace) {
            if ($trace->targetBin) {
                $targetBins->push($trace->targetBin);
                // Recursivamente obtener bins destino de los bins destino
                $targetBins = $targetBins->merge($trace->targetBin->getTargetBins());
            }
        }
        
        return $targetBins->unique('id');
    }

    // Obtener órdenes de procesamiento relacionadas
    public function getProcessOrders()
    {
        $orders = collect();
        $traceability = $this->targetTraceability()->with('processOrder')->get();
        
        foreach ($traceability as $trace) {
            if ($trace->processOrder) {
                $orders->push($trace->processOrder);
            }
        }
        
        // También incluir órdenes directas
        $orders = $orders->merge($this->orders);
        
        return $orders->unique('id');
    }

    // Obtener cadena completa de trazabilidad (árbol completo)
    public function getFullTraceabilityTree()
    {
        return [
            'bin' => $this,
            'sources' => $this->getSourceBins()->map(function($bin) {
                return [
                    'bin' => $bin,
                    'traceability' => $bin->getFullTraceabilityTree()
                ];
            }),
            'targets' => $this->getTargetBins()->map(function($bin) {
                return [
                    'bin' => $bin,
                    'traceability' => $bin->getFullTraceabilityTree()
                ];
            }),
            'process_orders' => $this->getProcessOrders(),
        ];
    }

    // Obtener proveedor original (el primero en la cadena de trazabilidad)
    public function getOriginalSupplier()
    {
        // Si este bin tiene trazabilidad hacia atrás, buscar el proveedor del bin más antiguo
        $sourceBins = $this->getSourceBins();
        
        if ($sourceBins->isEmpty()) {
            // Este es el bin original, retornar su proveedor
            return $this->supplier;
        }
        
        // Buscar recursivamente el proveedor original
        $originalSupplier = null;
        foreach ($sourceBins as $sourceBin) {
            $supplier = $sourceBin->getOriginalSupplier();
            if ($supplier) {
                $originalSupplier = $supplier;
                break; // Tomar el primero encontrado
            }
        }
        
        return $originalSupplier ?? $this->supplier;
    }

    // Obtener todos los proveedores involucrados en la cadena
    public function getAllSuppliersInChain()
    {
        $suppliers = collect();
        
        // Agregar el proveedor de este bin
        if ($this->supplier) {
            $suppliers->push($this->supplier);
        }
        
        // Agregar proveedores de bins fuente
        $sourceBins = $this->getSourceBins();
        foreach ($sourceBins as $sourceBin) {
            $suppliers = $suppliers->merge($sourceBin->getAllSuppliersInChain());
        }
        
        return $suppliers->unique('id');
    }

    // Obtener información de trazabilidad simplificada con proveedor
    public function getTraceabilityInfo()
    {
        $originalSupplier = $this->getOriginalSupplier();
        $sourceBins = $this->getSourceBins();
        $targetBins = $this->getTargetBins();
        $processOrders = $this->getProcessOrders();
        
        return [
            'current_bin' => [
                'id' => $this->id,
                'bin_number' => $this->current_bin_number,
                'tarja_number' => $this->tarja_number,
                'weight' => $this->current_weight,
                'calibre' => $this->current_calibre,
                'status' => $this->status,
                'supplier' => $this->supplier ? [
                    'id' => $this->supplier->id,
                    'name' => $this->supplier->name,
                    'internal_code' => $this->supplier->internal_code,
                    'csg_code' => $this->supplier->csg_code,
                ] : null,
            ],
            'original_supplier' => $originalSupplier ? [
                'id' => $originalSupplier->id,
                'name' => $originalSupplier->name,
                'internal_code' => $originalSupplier->internal_code,
                'csg_code' => $originalSupplier->csg_code,
            ] : null,
            'all_suppliers' => $this->getAllSuppliersInChain()->map(function($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'internal_code' => $supplier->internal_code,
                    'csg_code' => $supplier->csg_code,
                ];
            }),
            'source_bins_count' => $sourceBins->count(),
            'source_bins' => $sourceBins->map(function($bin) {
                return [
                    'id' => $bin->id,
                    'bin_number' => $bin->current_bin_number,
                    'tarja_number' => $bin->tarja_number,
                    'weight' => $bin->current_weight,
                    'supplier' => $bin->supplier ? $bin->supplier->name : null,
                ];
            }),
            'target_bins_count' => $targetBins->count(),
            'process_orders_count' => $processOrders->count(),
            'process_orders' => $processOrders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'plant' => $order->plant ? $order->plant->name : null,
                    'status' => $order->status,
                ];
            }),
        ];
    }

    // Get calibre display name
    public function getCalibreDisplayAttribute()
    {
        $calibres = [
            '80-90' => '80-90 unidades/libra',
            '120-x' => '120-x unidades/libra',
            '90-100' => '90-100 unidades/libra',
            '70-90' => '70-90 unidades/libra',
            'Grande 50-60' => 'Grande (50-60 unidades/libra)',
            'Mediana 40-50' => 'Mediana (40-50 unidades/libra)',
            'Pequeña 30-40' => 'Pequeña (30-40 unidades/libra)',
        ];

        return $calibres[$this->calibre] ?? $this->calibre;
    }

    // Get status display name
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'processed' => 'Procesado',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // Generate initial QR code for received bins with tarja information
    public function generateInitialQrCode()
    {
        // Solo codificar el ID y número de tarja para evitar "Data too big"
        // El resto de la información se puede obtener desde la base de datos
        $data = [
            'id' => $this->id,
            'tarja_number' => $this->tarja_number,
            'type' => 'tarja_internal',
            'version' => 1,
        ];

        // Encriptar solo los datos esenciales
        $encryptedData = Crypt::encrypt(json_encode($data));

        // Generate QR code image (SVG format, no imagick required)
        $qrService = app(\App\Services\QrCodeService::class);
        $filename = 'tarja_' . $this->id . '_' . time() . '.svg';
        $qrPath = $qrService->generateWithLogo($encryptedData, $filename);

        // Save QR code path and generation time
        $this->update([
            'qr_code' => $qrPath,
            'qr_generated_at' => now(),
            'qr_updated_at' => now(),
        ]);

        return $qrPath;
    }

    // Update QR code during processing (maintains history)
    public function updateQrCodeAfterProcessing($newBinNumber = null, $processedWeight = null, $processedCalibre = null)
    {
        // Add to processing history
        $history = $this->processing_history ?? [];
        $history[] = [
            'date' => now()->format('Y-m-d H:i:s'),
            'action' => 'processed',
            'previous_bin' => $this->current_bin_number,
            'new_bin' => $newBinNumber ?? $this->current_bin_number,
            'previous_weight' => $this->processed_weight ?? $this->original_weight,
            'new_weight' => $processedWeight,
            'previous_calibre' => $this->processed_calibre ?? $this->original_calibre,
            'new_calibre' => $processedCalibre,
        ];

        $data = [
            'id' => $this->id,
            'type' => 'bin_inventory',
            'status' => 'processed',
            'supplier' => $this->supplier->name,
            'supplier_id' => $this->supplier_id,
            'original_bin_number' => $this->original_bin_number,
            'current_bin_number' => $newBinNumber ?? $this->current_bin_number,
            'entry_date' => $this->entry_date->format('Y-m-d'),
            'original_weight' => $this->original_weight,
            'original_calibre' => $this->original_calibre,
            'received_at' => $this->received_at->format('Y-m-d H:i:s'),
            'processing_date' => now()->format('Y-m-d'),
            'processed_weight' => $processedWeight,
            'processed_calibre' => $processedCalibre,
            'processed_at' => now()->format('Y-m-d H:i:s'),
            'processing_history' => $history,
            'version' => 2,
        ];

        $encryptedData = Crypt::encrypt(json_encode($data));

        // Generate new QR code image (SVG format, no imagick required)
        $qrService = app(\App\Services\QrCodeService::class);
        $filename = 'bin_' . $this->id . '_v2_' . time() . '.svg';
        $qrPath = $qrService->generateWithLogo($encryptedData, $filename);

        // Update record
        $this->update([
            'current_bin_number' => $newBinNumber ?? $this->current_bin_number,
            'processing_date' => now(),
            'processed_weight' => $processedWeight,
            'processed_calibre' => $processedCalibre,
            'processed_at' => now(),
            'status' => 'processed',
            'processing_history' => $history,
            'qr_code' => $qrPath,
            'qr_updated_at' => now(),
        ]);

        return $qrPath;
    }

    // Get QR code URL
    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code ? asset('storage/' . $this->qr_code) : null;
    }

    // Decrypt QR code data
    public static function decryptQrData($encryptedData)
    {
        try {
            $decrypted = Crypt::decrypt($encryptedData);
            $data = json_decode($decrypted, true);

            // Ensure it's a valid tarja QR
            if (!is_array($data) || !isset($data['type']) || !in_array($data['type'], ['tarja_internal', 'bin_inventory'])) {
                return null;
            }

            // Si solo tiene ID, cargar los datos completos desde la base de datos
            if (isset($data['id']) && !isset($data['tarja_number'])) {
                $processedBin = self::find($data['id']);
                if ($processedBin) {
                    // Retornar datos completos desde la base de datos
                    return [
                        'id' => $processedBin->id,
                        'type' => 'tarja_internal',
                        'tarja_number' => $processedBin->tarja_number,
                        'lote' => $processedBin->lote,
                        'supplier_id' => $processedBin->supplier_id,
                        'supplier_name' => $processedBin->supplier->name ?? null,
                        'supplier_internal_code' => $processedBin->supplier->internal_code ?? null,
                        'supplier_csg_code' => $processedBin->supplier->csg_code ?? null,
                        'original_bin_number' => $processedBin->original_bin_number,
                        'current_bin_number' => $processedBin->current_bin_number,
                        'entry_date' => $processedBin->entry_date->format('Y-m-d'),
                        'original_weight' => $processedBin->original_weight,
                        'net_fruit_weight' => $processedBin->net_fruit_weight,
                        'gross_weight' => $processedBin->gross_weight,
                        'original_calibre' => $processedBin->original_calibre,
                        'unidades_per_pound_avg' => $processedBin->unidades_per_pound_avg,
                        'humidity' => $processedBin->humidity,
                        'trash_level' => $processedBin->trash_level,
                        'trash_level_stars' => $processedBin->trash_level_stars,
                        'vehicle_plate' => $processedBin->vehicle_plate,
                        'reception_batch_id' => $processedBin->reception_batch_id,
                        'received_at' => $processedBin->received_at->format('Y-m-d H:i:s'),
                        'status' => $processedBin->status,
                    ];
                }
            }

            return $data;
        } catch (\Exception $e) {
            return null;
        }
    }

    // Check if QR is valid and belongs to this system
    public static function validateQrData($data)
    {
        if (!is_array($data) || !isset($data['id']) || !isset($data['type'])) {
            return false;
        }

        if (!in_array($data['type'], ['tarja_internal', 'bin_inventory'])) {
            return false;
        }

        return self::where('id', $data['id'])->exists();
    }

    // Get current weight based on status
    public function getCurrentWeightAttribute()
    {
        return $this->processed_weight ?? $this->original_weight;
    }

    // Get current calibre based on status
    public function getCurrentCalibreAttribute()
    {
        return $this->processed_calibre ?? $this->original_calibre;
    }

    // Get display name for current bin number
    public function getDisplayBinNumberAttribute()
    {
        return $this->current_bin_number;
    }
}
