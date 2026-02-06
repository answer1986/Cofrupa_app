<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'payment_method',
        'reference_number',
        'amount',
        'currency',
        'payment_date',
        'invoice_number',
        'purchase_order',
        'payee_name',
        'payment_type',
        'payable_id',
        'payable_type',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Relación polimórfica: un pago puede estar asociado a FinancePurchase, FinanceSale, Purchase, etc.
     */
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helpers
    public function getPaymentMethodDisplayAttribute(): string
    {
        $methods = [
            'cheque' => 'Cheque',
            'transferencia' => 'Transferencia',
            'efectivo' => 'Efectivo',
            'tarjeta' => 'Tarjeta',
            'otro' => 'Otro',
        ];
        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    public function getStatusDisplayAttribute(): string
    {
        $statuses = [
            'pendiente' => 'Pendiente',
            'completado' => 'Completado',
            'rechazado' => 'Rechazado',
            'anulado' => 'Anulado',
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getCompanyDisplayAttribute(): string
    {
        $companies = [
            'cofrupa' => 'Cofrupa Export',
            'luis_gonzalez' => 'Luis Gonzalez',
            'comercializadora' => 'Comercializadora',
        ];
        return $companies[$this->company] ?? $this->company;
    }

    public function getPaymentTypeDisplayAttribute(): string
    {
        $types = [
            'compra' => 'Compra',
            'venta' => 'Venta',
            'gasto' => 'Gasto',
            'otro' => 'Otro',
        ];
        return $types[$this->payment_type] ?? $this->payment_type;
    }
}
