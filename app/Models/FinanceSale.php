<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'company', 'sale_date', 'invoice_number', 'contract_number', 'client_name',
        'caliber', 'type', 'kilos', 'destination_port', 'destination_country', 'destination',
        'exchange_rate', 'unit_price_clp', 'unit_price_usd', 'net_price_clp', 'net_price_usd',
        'total_sale_clp', 'total_sale_usd', 'iva_clp', 'gross_total', 'payment_usd',
        'balance_usd', 'paid', 'payment_term_days', 'payment_date', 'status', 'bank',
        'with_iva', 'notes'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'payment_date' => 'date',
        'kilos' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'unit_price_clp' => 'decimal:2',
        'unit_price_usd' => 'decimal:2',
        'net_price_clp' => 'decimal:2',
        'net_price_usd' => 'decimal:2',
        'total_sale_clp' => 'decimal:2',
        'total_sale_usd' => 'decimal:2',
        'iva_clp' => 'decimal:2',
        'gross_total' => 'decimal:2',
        'payment_usd' => 'decimal:2',
        'balance_usd' => 'decimal:2',
        'paid' => 'boolean',
        'payment_term_days' => 'integer',
        'with_iva' => 'boolean',
    ];

    public function getCompanyDisplayAttribute()
    {
        $names = [
            'cofrupa' => 'Cofrupa Export',
            'luis_gonzalez' => 'Luis Gonzalez',
            'comercializadora' => 'Comercializadora',
        ];
        return $names[$this->company] ?? $this->company;
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Pendiente',
            'paid' => 'Pagado',
            'partial' => 'Parcial',
            'cancelled' => 'Cancelado',
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}
