<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_name',
        'csg_code',
        'internal_code',
        'location',
        'phone',
        'business_type',
        'total_debt',
        'total_paid',
    ];

    protected $casts = [
        'total_debt' => 'decimal:2',
        'total_paid' => 'decimal:2',
    ];

    // Relationship with bins
    public function bins()
    {
        return $this->hasMany(Bin::class);
    }

    // Relationship with purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Relationship with processed bins
    public function processedBins()
    {
        return $this->hasMany(ProcessedBin::class);
    }

    public function binAssignments()
    {
        return $this->hasMany(BinAssignment::class);
    }

    public function currentBins()
    {
        return $this->hasManyThrough(Bin::class, BinAssignment::class, 'supplier_id', 'id', 'id', 'bin_id')
            ->whereNull('bin_assignments.return_date');
    }

    public function allBins()
    {
        return $this->hasManyThrough(Bin::class, BinAssignment::class, 'supplier_id', 'id', 'id', 'bin_id');
    }

    // Payment tracking methods
    public function getHasOverduePaymentsAttribute()
    {
        return $this->purchases->where('is_overdue', true)->count() > 0;
    }

    public function getHasUpcomingPaymentsAttribute()
    {
        return $this->purchases->where('amount_owed', '>', 0)
                              ->whereNotNull('payment_due_date')
                              ->where('payment_due_date', '>=', now())
                              ->where('payment_due_date', '<=', now()->addDays(7))
                              ->count() > 0;
    }

    // Calculate pending amount
    public function getPendingAmountAttribute()
    {
        return $this->total_debt - $this->total_paid;
    }

    // Get total weight purchased
    public function getTotalWeightPurchasedAttribute()
    {
        return $this->purchases->sum('weight_purchased');
    }
}
