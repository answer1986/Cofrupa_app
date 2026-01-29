<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'customs_agency',
        'address',
        'notes',
        'is_incomplete',
        'tax_id',
        'bank_name',
        'bank_account_type',
        'bank_account_number',
    ];

    protected $casts = [
        'is_incomplete' => 'boolean',
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

    public function contacts()
    {
        return $this->morphMany(EntityContact::class, 'entity')->orderBy('order');
    }

    // Accessors
    public function getTypeDisplayAttribute()
    {
        return $this->type === 'constant' ? 'Constante' : 'Nuevo';
    }
}
