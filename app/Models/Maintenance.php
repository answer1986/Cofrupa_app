<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'maintenance_type',
        'periodicity',
        'maintenance_date',
        'next_maintenance_date',
        'description',
        'observations',
        'cost',
        'technician',
        'user_id',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'cost' => 'decimal:2',
    ];

    // Relaciones
    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Métodos auxiliares
    public function getMaintenanceTypeDisplayAttribute()
    {
        $types = [
            'preventive' => 'Preventiva',
            'corrective' => 'Correctiva',
            'predictive' => 'Predictiva',
            'emergency' => 'Emergencia',
        ];

        return $types[$this->maintenance_type] ?? $this->maintenance_type;
    }

    public function getPeriodicityDisplayAttribute()
    {
        $periodicities = [
            'daily' => 'Diaria',
            'weekly' => 'Semanal',
            'monthly' => 'Mensual',
            'quarterly' => 'Trimestral',
            'biannual' => 'Semestral',
            'annual' => 'Anual',
            'as_needed' => 'Según necesidad',
        ];

        return $periodicities[$this->periodicity] ?? $this->periodicity;
    }

    public function isOverdue()
    {
        if (!$this->next_maintenance_date) {
            return false;
        }

        return $this->next_maintenance_date->isPast();
    }
}
