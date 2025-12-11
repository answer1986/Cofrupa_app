<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bin_id',
        'supplier_id',
        'delivery_date',
        'return_date',
        'weight_delivered',
        'weight_returned',
        'notes',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'return_date' => 'date',
        'weight_delivered' => 'decimal:2',
        'weight_returned' => 'decimal:2',
    ];

    public function bin()
    {
        return $this->belongsTo(Bin::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Get days since delivery
    public function getDaysSinceDeliveryAttribute()
    {
        if (!$this->delivery_date) {
            return null;
        }

        return $this->delivery_date->diffInDays(now());
    }

    // Check if assignment is overdue
    public function getIsOverdueAttribute()
    {
        if ($this->return_date) {
            return false; // Already returned
        }

        // Assume bins should be returned within 30 days
        return $this->delivery_date->addDays(30)->isPast();
    }
}
