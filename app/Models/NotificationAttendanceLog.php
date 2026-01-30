<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationAttendanceLog extends Model
{
    protected $table = 'notification_attendance_log';

    protected $fillable = [
        'user_id',
        'event_type',
        'event_label',
        'count_snapshot',
        'attended_at',
        'notes',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
        'count_snapshot' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tipos de evento que coinciden con la campana.
     */
    public static function eventTypes(): array
    {
        return [
            'pending_purchases' => ['label' => 'Compras pendientes', 'route' => 'purchases.index', 'param' => ['filter' => 'pending']],
            'pending_process_orders' => ['label' => 'Órdenes de proceso', 'route' => 'processing.orders.index'],
            'pending_plant_orders' => ['label' => 'Órdenes de producción', 'route' => 'processing.production-orders.index'],
            'pending_shipments' => ['label' => 'Envíos en curso', 'route' => 'shipments.index'],
            'draft_contracts' => ['label' => 'Contratos en borrador', 'route' => 'contracts.index'],
            'incomplete_suppliers' => ['label' => 'Proveedores por completar', 'route' => 'suppliers.index'],
            'incomplete_clients' => ['label' => 'Clientes por completar', 'route' => 'clients.index'],
        ];
    }
}
