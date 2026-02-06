<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyPurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_purchase_id',
        'name',
        'quantity',
        'unit',
        'unit_price',
        'total',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function supplyPurchase()
    {
        return $this->belongsTo(SupplyPurchase::class);
    }
}
