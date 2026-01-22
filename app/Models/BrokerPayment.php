<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrokerPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'broker_id',
        'contract_id',
        'document_type',
        'amount',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    // Relationships
    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    // Accessors
    public function getDocumentTypeDisplayAttribute()
    {
        return $this->document_type === 'original' ? 'Original' : 'Release';
    }
}
