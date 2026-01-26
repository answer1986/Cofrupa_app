<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'name',
        'code',
        'type',
        'brand',
        'model',
        'serial_number',
        'status',
        'purchase_date',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    // Relaciones (opcional - las máquinas son internas)
    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    // Métodos auxiliares
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'active' => 'Activa',
            'inactive' => 'Inactiva',
            'maintenance' => 'En Mantención',
            'retired' => 'Retirada',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getLastMaintenanceAttribute()
    {
        return $this->maintenances()->latest('maintenance_date')->first();
    }

    public function getNextMaintenanceAttribute()
    {
        return $this->maintenances()
            ->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->orderBy('next_maintenance_date')
            ->first();
    }
}
