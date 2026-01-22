<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'shipment_number',
        'shipping_line_id',
        'scheduled_date',
        'actual_date',
        'status',
        'plant_pickup_company',
        'customs_loading_company',
        'transport_company_id',
        'transport_company',
        'transport_contact',
        'transport_phone',
        'transport_email',
        'transport_request_number',
        'transport_notes',
        'plant_pickup_scheduled',
        'plant_pickup_actual',
        'customs_loading_scheduled',
        'customs_loading_actual',
        'transport_departure_scheduled',
        'transport_departure_actual',
        'port_arrival_scheduled',
        'port_arrival_actual',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'actual_date' => 'date',
        'plant_pickup_scheduled' => 'datetime',
        'plant_pickup_actual' => 'datetime',
        'customs_loading_scheduled' => 'datetime',
        'customs_loading_actual' => 'datetime',
        'transport_departure_scheduled' => 'datetime',
        'transport_departure_actual' => 'datetime',
        'port_arrival_scheduled' => 'datetime',
        'port_arrival_actual' => 'datetime',
    ];

    // Relationships
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function shippingLine()
    {
        return $this->belongsTo(ShippingLine::class);
    }

    public function logisticsCompany()
    {
        return $this->belongsTo(LogisticsCompany::class, 'transport_company_id');
    }

    public function stages()
    {
        return $this->hasMany(ShipmentStage::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function exportations()
    {
        return $this->hasMany(Exportation::class);
    }

    // Accessors
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'scheduled' => 'Programado',
            'in_transit' => 'En Tránsito',
            'at_customs' => 'En Aduana',
            'loaded' => 'Cargado',
            'shipped' => 'Embarcado',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}
