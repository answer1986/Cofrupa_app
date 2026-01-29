<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'company', 'purchase_date', 'invoice_number', 'supplier_name', 'product_caliber', 'type',
        'kilos', 'unit_price_clp', 'unit_price_usd', 'total_net_clp', 'total_net_usd',
        'iva', 'total_clp', 'total_usd', 'commission_per_kilo', 'total_commission',
        'freight_per_kilo', 'total_freight', 'other_costs', 'final_total', 'average_per_kilo',
        'status', 'with_iva', 'notes', 'exchange_rate', 'bank'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'kilos' => 'decimal:2',
        'unit_price_clp' => 'decimal:2',
        'unit_price_usd' => 'decimal:2',
        'total_net_clp' => 'decimal:2',
        'total_net_usd' => 'decimal:2',
        'iva' => 'decimal:2',
        'total_clp' => 'decimal:2',
        'total_usd' => 'decimal:2',
        'commission_per_kilo' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'freight_per_kilo' => 'decimal:2',
        'total_freight' => 'decimal:2',
        'other_costs' => 'decimal:2',
        'final_total' => 'decimal:2',
        'average_per_kilo' => 'decimal:2',
        'exchange_rate' => 'decimal:2',
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
