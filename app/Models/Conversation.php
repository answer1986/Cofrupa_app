<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'broker_id',
        'user_id',
        'stage',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getStageDisplayAttribute()
    {
        $stages = [
            'client_contact' => 'Contacto con Cliente',
            'stock_offer' => 'Oferta de Stock',
            'negotiation' => 'Negociación',
        ];
        return $stages[$this->stage] ?? $this->stage;
    }
}
