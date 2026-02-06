<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'process_order_id',
        'driver_name',
        'vehicle_plate',
        'guide_number',
        'shipment_date',
        'destination',
        'total_kilos',
        'bin_type',
        'shipment_cost',
        'payment_status',
        'notes',
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'total_kilos' => 'decimal:2',
        'shipment_cost' => 'decimal:2',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function processOrder()
    {
        return $this->belongsTo(ProcessOrder::class);
    }

    public function bins()
    {
        return $this->belongsToMany(ProcessedBin::class, 'plant_shipment_bins')
            ->withPivot('kilos_sent')
            ->withTimestamps();
    }
}
