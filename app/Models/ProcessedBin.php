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
