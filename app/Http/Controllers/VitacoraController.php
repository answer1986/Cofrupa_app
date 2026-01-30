<?php

namespace App\Http\Controllers;

use App\Models\NotificationAttendanceLog;
use App\Models\Purchase;
use App\Models\ProcessOrder;
use App\Models\PlantProductionOrder;
use App\Models\Shipment;
use App\Models\Contract;
use App\Models\Supplier;
use App\Models\Client;
use Illuminate\Http\Request;

class VitacoraController extends Controller
{
    /**
     * Bitácora de eventos de la campana: pendientes actuales y historial de atenciones.
     */
    public function index()
    {
        $counts = $this->getPendingCounts();
        $eventTypes = NotificationAttendanceLog::eventTypes();

        // Para cada tipo, última vez que el usuario lo marcó como atendido
        $lastAttended = [];
        foreach (array_keys($eventTypes) as $type) {
            $log = NotificationAttendanceLog::where('user_id', auth()->id())
                ->where('event_type', $type)
                ->latest('attended_at')
                ->first();
            $lastAttended[$type] = $log;
        }

        // Historial de atenciones (todas las del usuario, más recientes primero)
        $history = NotificationAttendanceLog::where('user_id', auth()->id())
            ->with('user:id,name')
            ->latest('attended_at')
            ->paginate(20);

        return view('vitacora.index', compact('counts', 'eventTypes', 'lastAttended', 'history'));
    }

    /**
     * Marcar un evento como atendido.
     */
    public function store(Request $request)
    {
        $eventTypes = NotificationAttendanceLog::eventTypes();
        $validTypes = array_keys($eventTypes);

        $request->validate([
            'event_type' => 'required|string|in:' . implode(',', $validTypes),
            'notes' => 'nullable|string|max:500',
        ]);

        $type = $request->event_type;
        $info = $eventTypes[$type];
        $counts = $this->getPendingCounts();
        $countKey = $this->eventTypeToCountKey($type);
        $countSnapshot = $counts[$countKey] ?? 0;

        NotificationAttendanceLog::create([
            'user_id' => auth()->id(),
            'event_type' => $type,
            'event_label' => $info['label'],
            'count_snapshot' => $countSnapshot,
            'attended_at' => now(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('vitacora.index')
            ->with('success', 'Registrado como atendido: ' . $info['label']);
    }

    private function getPendingCounts(): array
    {
        return [
            'pending_purchases' => Purchase::where(function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNull('unit_price')->orWhereNull('total_amount');
                })->where(function ($q2) {
                    $q2->where('payment_status', '!=', 'paid')->orWhereNull('payment_status');
                });
            })->count(),
            'pending_process_orders' => ProcessOrder::whereIn('status', ['pending', 'in_progress'])->count(),
            'pending_plant_orders' => PlantProductionOrder::whereIn('status', ['pending', 'in_progress'])->count(),
            'pending_shipments' => Shipment::whereIn('status', ['scheduled', 'in_transit', 'at_customs', 'loaded'])->count(),
            'draft_contracts' => Contract::where('status', 'draft')->count(),
            'incomplete_suppliers' => Supplier::where('is_incomplete', true)
                ->where(function ($q) {
                    $q->whereNull('location')->orWhere('location', '');
                })->count(),
            'incomplete_clients' => Client::where('is_incomplete', true)->count(),
        ];
    }

    private function eventTypeToCountKey(string $type): string
    {
        return $type; // coinciden con las claves de getPendingCounts
    }
}
