<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'stage_name',
        'stage_type',
        'scheduled_time',
        'actual_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'actual_time' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Pendiente',
            'in_progress' => 'En Progreso',
            'completed' => 'Completado',
            'delayed' => 'Retrasado',
            'cancelled' => 'Cancelado',
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}
