<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'process_order_id', 'invoice_number', 'order_number', 'amount', 'currency',
        'exchange_rate', 'is_paid', 'payment_date', 'invoice_date', 'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'is_paid' => 'boolean',
        'payment_date' => 'date',
        'invoice_date' => 'date',
    ];

    public function processOrder()
    {
        return $this->belongsTo(ProcessOrder::class);
    }
}
