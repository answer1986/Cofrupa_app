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
        'address',
        'notes',
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

    // Accessors
    public function getTypeDisplayAttribute()
    {
        return $this->type === 'constant' ? 'Constante' : 'Nuevo';
    }
}
