<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id', 'supplier_id', 'contract_id', 'order_number', 'csg_code', 'production_days',
        'order_date', 'expected_completion_date', 'actual_completion_date',
        'status', 'progress_percentage', 'product_description', 'quantity', 'unit', 'notes', 'alert_sent',
        'raw_material', 'product', 'type', 'caliber', 'quality', 'labeling',
        'packaging', 'potassium_sorbate', 'humidity', 'stone_percentage',
        'oil', 'damage', 'plant_print', 'destination', 'loading_date', 'sag',
        'kilos_sent', 'kilos_produced', 'vehicle_plate', 'shipment_date', 'shipment_time'
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'shipment_date' => 'date',
        'production_days' => 'integer',
        'progress_percentage' => 'integer',
        'quantity' => 'decimal:2',
        'kilos_sent' => 'decimal:2',
        'kilos_produced' => 'decimal:2',
        'alert_sent' => 'boolean',
        'sag' => 'boolean',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function invoices()
    {
        return $this->hasMany(ProcessInvoice::class);
    }

    public function tarjas()
    {
        return $this->belongsToMany(ProcessedBin::class, 'order_tarjas', 'process_order_id', 'processed_bin_id')
            ->withPivot('quantity_kg')
            ->withTimestamps();
    }

    public function getStatusDisplayAttribute()
    {
        switch($this->status) {
            case 'pending':
                return 'Pendiente';
            case 'in_progress':
                return 'En Proceso';
            case 'completed':
                return 'Completado';
            case 'cancelled':
                return 'Cancelado';
            default:
                return $this->status;
        }
    }
}
