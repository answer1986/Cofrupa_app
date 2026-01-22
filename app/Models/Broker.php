<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'commission_percentage',
        'email',
        'phone',
        'address',
        'notes',
        'tax_id',
        'bank_name',
        'bank_account_type',
        'bank_account_number',
    ];

    protected $casts = [
        'commission_percentage' => 'decimal:2',
    ];

    // Relationships
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function payments()
    {
        return $this->hasMany(BrokerPayment::class);
    }
}
