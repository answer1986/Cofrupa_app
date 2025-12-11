<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bin extends Model
{
    use HasFactory;

    protected $fillable = [
        'bin_number',
        'type',
        'ownership_type',
        'weight_capacity',
        'current_weight',
        'supplier_id',
        'status',
        'photo_path',
        'delivery_date',
        'return_date',
        'damage_description',
        'notes',
    ];

    protected $casts = [
        'weight_capacity' => 'decimal:2',
        'current_weight' => 'decimal:2',
        'delivery_date' => 'date',
        'return_date' => 'date',
    ];

    // Relationship with supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship with purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Get total purchase weight for this bin
    public function getTotalPurchaseWeightAttribute()
    {
        return $this->purchases()->sum('weight_purchased');
    }

    // Get weight capacity based on type (default empty bin weight)
    public static function getWeightCapacityForType($type)
    {
        // Por defecto, usar el peso del bin vacío
        return self::getEmptyBinWeight($type);
    }

    // Get empty bin weight (tare weight) based on type
    public static function getEmptyBinWeight($type)
    {
        // Peso del bin vacío (tara)
        return $type === 'wood' ? 6.00 : 3.00; // Madera: 6kg, Plástico: 3kg
    }
    
    // Get bin weight (container weight regardless of content)
    public static function getBinWeight($type)
    {
        // Peso del bin (contenedor) independiente del contenido
        return $type === 'wood' ? 60.00 : 45.00; // Madera: 60kg, Plástico: 45kg
    }

    // Get status display name
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'available' => 'Disponible',
            'in_use' => 'En uso',
            'maintenance' => 'Mantenimiento',
            'damaged' => 'Dañado',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // Get type display name
    public function getTypeDisplayAttribute()
    {
        return $this->type === 'wood' ? 'Madera' : 'Plástico';
    }

    // Get ownership type display name
    public function getOwnershipTypeDisplayAttribute()
    {
        $types = [
            'supplier' => 'Proveedor',
            'internal' => 'Interno',
            'field' => 'Campo',
        ];
        
        return $types[$this->ownership_type] ?? $this->ownership_type;
    }

    // Check if bin is internal
    public function getIsInternalAttribute()
    {
        return $this->ownership_type === 'internal';
    }

    // Check if bin belongs to supplier
    public function getIsSupplierBinAttribute()
    {
        return $this->ownership_type === 'supplier';
    }

    // Check if bin is field bin (delivered to suppliers)
    public function getIsFieldBinAttribute()
    {
        return $this->ownership_type === 'field';
    }

    // Check if bin is overdue for return
    public function getIsOverdueAttribute()
    {
        if (!$this->delivery_date || $this->return_date) {
            return false;
        }

        // Assume bins should be returned within 30 days
        return $this->delivery_date->addDays(30)->isPast();
    }

    // Relationships
    public function assignments()
    {
        return $this->hasMany(BinAssignment::class);
    }

    public function currentAssignment()
    {
        return $this->hasOne(BinAssignment::class)->whereNull('return_date');
    }

    // Get days since delivery
    public function getDaysSinceDeliveryAttribute()
    {
        if (!$this->delivery_date) {
            return null;
        }

        return $this->delivery_date->diffInDays(now());
    }
}
