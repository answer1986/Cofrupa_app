<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'contract_id', 'transaction_type', 'transaction_date', 'closing_date',
        'product_description', 'size_range', 'price_per_kg', 'quantity_kg', 'total_amount',
        'currency', 'exchange_rate', 'advance_payment', 'remaining_amount', 'payment_method',
        'bank_name', 'bank_account', 'payment_due_date', 'actual_payment_date', 'payment_status', 'notes'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'closing_date' => 'date',
        'payment_due_date' => 'date',
        'actual_payment_date' => 'date',
        'price_per_kg' => 'decimal:2',
        'quantity_kg' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'advance_payment' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function getTransactionTypeDisplayAttribute()
    {
        switch($this->transaction_type) {
            case 'purchase':
                return 'Compra';
            case 'sale':
                return 'Venta';
            case 'payment':
                return 'Pago';
            case 'advance':
                return 'Abono';
            default:
                return $this->transaction_type;
        }
    }

    public function getPaymentStatusDisplayAttribute()
    {
        switch($this->payment_status) {
            case 'pending':
                return 'Pendiente';
            case 'partial':
                return 'Parcial';
            case 'paid':
                return 'Pagado';
            default:
                return $this->payment_status;
        }
    }
}
