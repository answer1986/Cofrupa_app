<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessOrderSupply extends Model
{
    protected $fillable = [
        'process_order_id',
        'supply_purchase_item_id',
        'name',
        'quantity',
        'unit',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function processOrder()
    {
        return $this->belongsTo(ProcessOrder::class);
    }

    public function supplyPurchaseItem()
    {
        return $this->belongsTo(SupplyPurchaseItem::class);
    }
}
