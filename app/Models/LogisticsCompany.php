<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'contact_name',
        'contact_email',
        'contact_phone',
        'address',
        'notes',
        'is_active',
        'tax_id',
        'bank_name',
        'bank_account_type',
        'bank_account_number',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'transport_company_id');
    }

    public function contacts()
    {
        return $this->morphMany(EntityContact::class, 'entity')->orderBy('order');
    }
}
