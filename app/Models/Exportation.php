<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'contract_id',
        'export_number',
        'folder_path',
        'status',
        'export_date',
        'notes',
    ];

    protected $casts = [
        'export_date' => 'date',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function documents()
    {
        return $this->hasMany(ExportationDocument::class);
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'preparation' => 'En Preparación',
            'in_progress' => 'En Progreso',
            'completed' => 'Completada',
            'cancelled' => 'Cancelada',
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}
