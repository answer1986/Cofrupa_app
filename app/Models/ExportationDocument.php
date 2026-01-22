<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'exportation_id',
        'document_type',
        'document_number',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'status',
        'uploaded_at',
        'validated_at',
        'validation_notes',
        'notes',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'uploaded_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    public function exportation()
    {
        return $this->belongsTo(Exportation::class);
    }

    public function getDocumentTypeDisplayAttribute()
    {
        $types = [
            'v1' => 'V1 (Declaración de Exportación)',
            'commercial_invoice' => 'Factura Comercial',
            'origin_certificate' => 'Certificado de Origen',
            'phytosanitary' => 'Fitosanitario',
            'quality_certificate' => 'Certificado de Calidad',
            'packing_list' => 'Packing List',
            'eur1' => 'EUR1',
            'contract_specific' => 'Documentos Específicos del Contrato',
        ];
        return $types[$this->document_type] ?? $this->document_type;
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'pending' => 'Pendiente',
            'uploaded' => 'Subido',
            'validated' => 'Validado',
            'approved' => 'Aprobado',
        ];
        return $statuses[$this->status] ?? $this->status;
    }
}
