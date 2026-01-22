<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'document_number',
        'document_type',
        'recipient',
        'recipient_company',
        'content',
        'file_path',
        'status',
        'generated_at',
        'sent_at',
        'notes',
    ];

    protected $casts = [
        'content' => 'array',
        'generated_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function getDocumentTypeDisplayAttribute()
    {
        $types = [
            'export_guide_plant' => 'Guía de Exportación (Planta)',
            'export_guide_transport' => 'Guía de Exportación (Transporte)',
            'customs_loading' => 'Documentos de Carga (Aduana)',
            'dvl_matrix' => 'Matriz DVL',
            'master_document' => 'Documento Maestro',
        ];
        return $types[$this->document_type] ?? $this->document_type;
    }

    public function getRecipientDisplayAttribute()
    {
        $recipients = [
            'plant' => 'Planta',
            'customs' => 'Aduana',
            'transport' => 'Transporte',
            'embarkation' => 'Embarque',
        ];
        return $recipients[$this->recipient] ?? $this->recipient;
    }
}
