<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProcessOrder;
use Carbon\Carbon;

class PlantProductionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'process_order_id', 'contract_id', 'plant_id', 'order_number', 'product', 'output_caliber',
        'order_quantity_kg', 'booking_number', 'vessel', 'entry_date', 'entry_time',
        'completion_date', 'completion_time', 'production_program', 'sorbate_solution',
        'delay_hours', 'delay_reason', 'produced_kilos', 'output_quantity_kg', 'discard_kg', 'discard_reason',
        'discard_status', 'discard_recovery_date', 'discard_notes', 'nominal_kg_per_hour',
        'estimated_hours', 'actual_hours', 'day_of_week', 'status', 'has_delay', 'notes',
        'discard_humid_kg', 'discard_stone_kg', 'discard_no_sorbate_kg', 'discard_other_kg',
        'dispatched_kg', 'dispatch_date', 'boxes_in_plant', 'boxes_weight_kg'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'completion_date' => 'date',
        'discard_recovery_date' => 'date',
        'dispatch_date' => 'date',
        'order_quantity_kg' => 'decimal:2',
        'sorbate_solution' => 'decimal:2',
        'delay_hours' => 'decimal:2',
        'produced_kilos' => 'decimal:2',
        'output_quantity_kg' => 'decimal:2',
        'discard_kg' => 'decimal:2',
        'discard_humid_kg' => 'decimal:2',
        'discard_stone_kg' => 'decimal:2',
        'discard_no_sorbate_kg' => 'decimal:2',
        'discard_other_kg' => 'decimal:2',
        'dispatched_kg' => 'decimal:2',
        'boxes_weight_kg' => 'decimal:2',
        'nominal_kg_per_hour' => 'decimal:2',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'has_delay' => 'boolean',
        'boxes_in_plant' => 'integer',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function processOrder()
    {
        return $this->belongsTo(ProcessOrder::class);
    }

    public function getStatusDisplayAttribute()
    {
        switch($this->status) {
            case 'pending':
                return 'Pendiente';
            case 'in_progress':
                return 'En Proceso';
            case 'completed':
                return 'Completado';
            case 'delayed':
                return 'Retrasado';
            case 'cancelled':
                return 'Cancelado';
            default:
                return $this->status;
        }
    }

    public function calculateDelay()
    {
        // Calcular horas estimadas basadas en cantidad y kg/hora nominal
        if ($this->nominal_kg_per_hour && $this->order_quantity_kg) {
            $this->estimated_hours = round($this->order_quantity_kg / $this->nominal_kg_per_hour, 2);
        }

        // Calcular horas reales si tenemos fechas y horas
        if ($this->entry_date && $this->entry_time && $this->completion_date && $this->completion_time) {
            try {
                $entryDateTime = Carbon::parse($this->entry_date->format('Y-m-d') . ' ' . $this->entry_time);
                $completionDateTime = Carbon::parse($this->completion_date->format('Y-m-d') . ' ' . $this->completion_time);
                
                if ($completionDateTime->greaterThan($entryDateTime)) {
                    $this->actual_hours = round($entryDateTime->diffInHours($completionDateTime, true), 2);
                }
            } catch (\Exception $e) {
                // Si hay error al parsear, no calcular
            }
        }

        // Calcular atraso
        if ($this->estimated_hours && $this->actual_hours) {
            $this->delay_hours = round($this->actual_hours - $this->estimated_hours, 2);
            $this->has_delay = $this->delay_hours > 0;
        }

        // Calcular día de la semana
        if ($this->completion_date) {
            $days = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
            $this->day_of_week = $days[$this->completion_date->dayOfWeek];
        }

        $this->save();
    }

    public function getDayOfWeekAttribute($value)
    {
        if (!$value && $this->completion_date) {
            $days = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
            return $days[$this->completion_date->dayOfWeek];
        }
        return $value;
    }

    public function getDiscardStatusDisplayAttribute()
    {
        switch($this->discard_status) {
            case 'pending':
                return 'Pendiente Recuperación';
            case 'recovered':
                return 'Recuperado';
            case 'disposed':
                return 'Desechado';
            default:
                return $this->discard_status;
        }
    }

    public function getEfficiencyPercentageAttribute()
    {
        if ($this->order_quantity_kg > 0) {
            return round(($this->produced_kilos / $this->order_quantity_kg) * 100, 2);
        }
        return 0;
    }

    public function getDiscardPercentageAttribute()
    {
        if ($this->order_quantity_kg > 0) {
            return round(($this->discard_kg / $this->order_quantity_kg) * 100, 2);
        }
        return 0;
    }
}
