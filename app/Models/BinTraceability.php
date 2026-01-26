<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinTraceability extends Model
{
    use HasFactory;

    protected $table = 'bin_traceability';

    protected $fillable = [
        'source_bin_id',
        'target_bin_id',
        'process_order_id',
        'purchase_id',
        'operation_type',
        'weight_kg',
        'operation_date',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
        'operation_date' => 'datetime',
    ];

    // Relaciones
    public function sourceBin()
    {
        return $this->belongsTo(ProcessedBin::class, 'source_bin_id');
    }

    public function targetBin()
    {
        return $this->belongsTo(ProcessedBin::class, 'target_bin_id');
    }

    public function processOrder()
    {
        return $this->belongsTo(ProcessOrder::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tipos de operación
    const OPERATION_INITIAL = 'initial'; // Recepción inicial desde compra
    const OPERATION_MIXING = 'mixing'; // Mezcla de bins
    const OPERATION_PROCESSING = 'processing'; // Envío a procesar

    // Obtener nombre de operación
    public function getOperationDisplayAttribute()
    {
        $operations = [
            self::OPERATION_INITIAL => 'Recepción Inicial',
            self::OPERATION_MIXING => 'Mezcla de Bins',
            self::OPERATION_PROCESSING => 'Envío a Procesar',
        ];

        return $operations[$this->operation_type] ?? $this->operation_type;
    }

    // Método estático para registrar trazabilidad
    public static function record($operationType, $data = [])
    {
        return self::create([
            'source_bin_id' => $data['source_bin_id'] ?? null,
            'target_bin_id' => $data['target_bin_id'] ?? null,
            'process_order_id' => $data['process_order_id'] ?? null,
            'purchase_id' => $data['purchase_id'] ?? null,
            'operation_type' => $operationType,
            'weight_kg' => $data['weight_kg'] ?? null,
            'operation_date' => $data['operation_date'] ?? now(),
            'user_id' => $data['user_id'] ?? auth()->id(),
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
