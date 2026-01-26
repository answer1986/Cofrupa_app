<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_type',
        'entity_id',
        'contact_person',
        'phone',
        'email',
        'position',
        'order',
    ];

    public function entity()
    {
        return $this->morphTo();
    }
}
