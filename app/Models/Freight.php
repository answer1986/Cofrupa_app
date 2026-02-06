<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freight extends Model
{
    use HasFactory;

    protected $fillable = [
        'freight_type',
        'origin',
        'destination',
        'driver_name',
        'vehicle_plate',
        'logistics_company_id',
        'freight_cost',
        'payment_status',
        'freight_date',
        'kilos',
        'guide_number',
        'purchase_id',
        'process_order_id',
        'shipment_id',
        'plant_shipment_id',
        'supply_purchase_id',
        'notes',
    ];

    protected $casts = [
        'freight_date' => 'date',
        'freight_cost' => 'decimal:2',
        'kilos' => 'decimal:2',
    ];

    // Relaciones
    public function logisticsCompany()
    {
        return $this->belongsTo(LogisticsCompany::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function processOrder()
    {
        return $this->belongsTo(ProcessOrder::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function plantShipment()
    {
        return $this->belongsTo(PlantShipment::class);
    }

    public function supplyPurchase()
    {
        return $this->belongsTo(SupplyPurchase::class);
    }

    // Accessors
    public function getFreightTypeDisplayAttribute()
    {
        $types = [
            'reception' => 'Recepción de Fruta',
            'to_processing' => 'Envío a Planta Procesamiento',
            'to_port' => 'Envío a Puerto',
            'supply_purchase' => 'Compra de Insumos',
            'other' => 'Otro',
        ];
        return $types[$this->freight_type] ?? $this->freight_type;
    }

    public function getPaymentStatusDisplayAttribute()
    {
        return $this->payment_status === 'paid' ? 'Pagado' : 'Pendiente';
    }
}
