<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_date',
        'supplier_name',
        'invoice_number',
        'buyer',
        'total_amount',
        'amount_paid',
        'amount_owed',
        'payment_status',
        'payment_due_date',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'payment_due_date' => 'date',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_owed' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(SupplyPurchaseItem::class);
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Pendiente',
            'partial' => 'Parcial',
            'paid' => 'Pagado',
        ];
        return $statuses[$this->payment_status] ?? $this->payment_status;
    }
}
