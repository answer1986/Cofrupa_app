<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceBankDebt extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'bank',
        'amount_usd',
        'due_date',
        'type',
        'notes',
    ];

    protected $casts = [
        'amount_usd' => 'decimal:2',
        'due_date' => 'date',
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

    public function getTypeDisplayAttribute()
    {
        $types = [
            'compra' => 'Compra',
            'venta' => 'Venta',
            'general' => 'General',
        ];
        return $types[$this->type] ?? $this->type;
    }
}
