<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ContractDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'document_type',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
        'notes',
        'uploaded_by',
    ];

    // Tipos de documentos permitidos
    const DOCUMENT_TYPES = [
        'transport' => 'Documento de Transporte',
        'naviera' => 'Documento de Naviera',
        'contrato' => 'Contrato',
        'calidad' => 'Certificado de Calidad',
        'sac' => 'Documento SAC',
        'envio' => 'Documento de Envío',
        'instructivo_embarque' => 'Instructivo de Embarque',
        'instructivo_carga' => 'Instructivo de Carga',
        'post_despacho' => 'Documentos Post-Despacho',
        'bill_of_lading' => 'Bill of Lading',
        'packing_list' => 'Packing List',
        'invoice' => 'Invoice/Factura',
        'certificate_origin' => 'Certificado de Origen',
        'phytosanitary' => 'Certificado Fitosanitario',
        'otros' => 'Otros Documentos',
    ];

    // Relationships
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Accessors
    public function getDocumentTypeDisplayAttribute()
    {
        return self::DOCUMENT_TYPES[$this->document_type] ?? $this->document_type;
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    // Methods
    public function deleteFile()
    {
        if (Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
    }
}
