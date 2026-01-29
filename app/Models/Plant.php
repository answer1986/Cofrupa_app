<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'address', 'phone', 'email', 'contact_person', 'notes', 'is_active',
        'tax_id', 'bank_name', 'bank_account_type', 'bank_account_number', 'payment_currency'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function processOrders()
    {
        return $this->hasMany(ProcessOrder::class);
    }

    public function contacts()
    {
        return $this->hasMany(PlantContact::class)->orderBy('order');
    }
}
