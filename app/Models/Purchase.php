<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'purchase_order',
        'bin_ids', // JSON array of bin IDs
        'purchase_date',
        'weight_purchased',
        'calibre',
        'units_per_pound',
        'unit_price',
        'total_amount',
        'amount_paid',
        'amount_owed',
        'payment_status',
        'payment_date',
        'payment_due_date',
        'notes',
        'supplier_bins_count',
        'supplier_bins_photo',
        'bins_to_send',
        'currency',
        'purchase_type',
        'buyer',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'weight_purchased' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_owed' => 'decimal:2',
        'payment_date' => 'date',
        'payment_due_date' => 'date',
        'bin_ids' => 'array', // Cast to array for multiple bins
        'bins_to_send' => 'array', // Cast to array for multiple bin requests
    ];

    // Relationship with supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship with bins (multiple)
    public function bins()
    {
        return $this->belongsToMany(Bin::class, 'purchase_bins');
    }

    // Relationship with processed bins
    public function processedBins()
    {
        return $this->hasMany(ProcessedBin::class);
    }

    // Legacy relationship for backward compatibility
    public function bin()
    {
        return $this->belongsTo(Bin::class);
    }

    // Calculate total amount if not set
    public function getCalculatedTotalAmountAttribute()
    {
        if ($this->total_amount) {
            return $this->total_amount;
        }

        if ($this->unit_price && $this->weight_purchased) {
            return $this->unit_price * $this->weight_purchased;
        }

        return 0;
    }

    // Calculate owed amount
    public function getCalculatedOwedAmountAttribute()
    {
        return $this->calculated_total_amount - $this->amount_paid;
    }

    // Get calibre display name
    public function getCalibreDisplayAttribute()
    {
        $calibres = [
            '80-90' => '80-90 unidades/libra',
            '120-x' => '120-x unidades/libra',
            '90-100' => '90-100 unidades/libra',
            '70-90' => '70-90 unidades/libra',
            'Grande 50-60' => 'Grande (50-60 unidades/libra)',
            'Mediana 40-50' => 'Mediana (40-50 unidades/libra)',
            'Pequeña 30-40' => 'Pequeña (30-40 unidades/libra)',
        ];

        return $calibres[$this->calibre] ?? $this->calibre;
    }

    // Get bins display (for showing multiple bins)
    public function getBinsDisplayAttribute()
    {
        if ($this->bins->count() > 0) {
            return $this->bins->pluck('bin_number')->join(', ');
        }

        // Fallback for legacy bin_id or bin_ids JSON
        if ($this->bin_ids) {
            $bins = Bin::whereIn('id', $this->bin_ids)->get();
            return $bins->pluck('bin_number')->join(', ');
        }

        return $this->bin ? $this->bin->bin_number : 'N/A';
    }

    // Payment tracking methods
    public function getDaysUntilDueAttribute()
    {
        if (!$this->payment_due_date) {
            return null;
        }

        return now()->diffInDays($this->payment_due_date, false);
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->payment_due_date) {
            return false;
        }

        return $this->payment_due_date->isPast() && $this->calculated_owed_amount > 0;
    }

    public function getPaymentUrgencyAttribute()
    {
        $days = $this->days_until_due;

        if ($this->is_overdue) {
            return 'overdue';
        } elseif ($days <= 3) {
            return 'urgent';
        } elseif ($days <= 7) {
            return 'warning';
        } else {
            return 'normal';
        }
    }

    public function getPaymentUrgencyColorAttribute()
    {
        $urgencies = [
            'overdue' => 'danger',
            'urgent' => 'warning',
            'warning' => 'info',
        ];

        return $urgencies[$this->payment_urgency] ?? 'secondary';
    }

    public function getPaymentUrgencyTextAttribute()
    {
        if ($this->is_overdue) {
            return 'Vencido';
        }

        $days = $this->days_until_due;
        if ($days === 0) {
            return 'Vence hoy';
        } elseif ($days === 1) {
            return 'Vence mañana';
        } elseif ($days > 1) {
            return "Vence en {$days} días";
        }

        return 'Sin fecha límite';
    }
}
