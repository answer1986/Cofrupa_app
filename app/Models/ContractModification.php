<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractModification extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'user_id',
        'field_changed',
        'old_value',
        'new_value',
        'notes',
    ];

    // Relationships
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
