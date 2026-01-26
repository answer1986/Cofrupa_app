<?php

namespace App\Http\Controllers;

use App\Models\AccountingRecord;
use App\Models\Purchase;
use App\Models\ProcessInvoice;
use App\Models\Contract;
use App\Models\BrokerPayment;
use App\Models\Shipment;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingRecordController extends Controller
{
    public function index(Request $request)
    {
        // Calcular totales de compras (COSTOS)
        $totalCompras = Purchase::whereIn('payment_status', ['paid', 'partial'])
            ->sum('total_amount');
        
        $totalComprasPendientes = Purchase::whereIn('payment_status', ['pending', 'partial'])
            ->sum('amount_owed');

        // Calcular costos de proceso en plantas (COSTOS)
        $totalCostoProceso = ProcessInvoice::sum('amount');
        $totalProcesosPendientes = ProcessInvoice::where('is_paid', false)->sum('amount');

        // Calcular comisiones de brokers (COSTOS)
        $totalComisionesBroker = BrokerPayment::sum('amount');

        // Calcular costos de logística (COSTOS) - freight_amount está en contracts
        $totalCostosLogistica = Contract::whereIn('status', ['active', 'completed'])
            ->sum('freight_amount');

        // Calcular costos de camión (COSTOS) - truck_cost está en shipments
        $totalCostosCamion = Shipment::whereNotNull('truck_cost')
            ->sum('truck_cost');

        // Calcular ventas (INGRESOS)
        $totalVentas = Contract::whereIn('status', ['active', 'completed'])
            ->sum(DB::raw('stock_committed * price'));

        // MARGEN BRUTO
        $totalCostos = $totalCompras + $totalCostoProceso + $totalComisionesBroker + $totalCostosLogistica + $totalCostosCamion;
        $margenBruto = $totalVentas - $totalCostos;
        $porcentajeMargen = $totalVentas > 0 ? ($margenBruto / $totalVentas) * 100 : 0;

        // Obtener transacciones recientes
        $query = AccountingRecord::with(['supplier', 'contract']);

        // Filtros
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $transactions = $query->latest('transaction_date')->paginate(20);

        $suppliers = Supplier::all();

        $stats = [
            'total_compras' => $totalCompras,
            'total_compras_pendientes' => $totalComprasPendientes,
            'total_costo_proceso' => $totalCostoProceso,
            'total_procesos_pendientes' => $totalProcesosPendientes,
            'total_comisiones_broker' => $totalComisionesBroker,
            'total_costos_logistica' => $totalCostosLogistica,
            'total_costos_camion' => $totalCostosCamion,
            'total_ventas' => $totalVentas,
            'total_costos' => $totalCostos,
            'margen_bruto' => $margenBruto,
            'porcentaje_margen' => $porcentajeMargen,
        ];

        return view('processing.accounting.index', compact('transactions', 'stats', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $contracts = Contract::whereIn('status', ['active', 'completed'])->get();
        
        return view('processing.accounting.create', compact('suppliers', 'contracts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'transaction_type' => 'required|in:purchase,sale,payment,advance',
            'transaction_date' => 'required|date',
            'closing_date' => 'nullable|date',
            'product_description' => 'nullable|string',
            'size_range' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
            'quantity_kg' => 'required|numeric|min:0',
            'currency' => 'required|in:USD,CLP',
            'exchange_rate' => 'nullable|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'payment_due_date' => 'nullable|date',
            'actual_payment_date' => 'nullable|date',
            'payment_status' => 'required|in:pending,partial,paid',
            'notes' => 'nullable|string',
        ]);

        // Calcular monto total
        $validated['total_amount'] = $validated['price_per_kg'] * $validated['quantity_kg'];
        
        // Calcular monto restante
        if (isset($validated['advance_payment'])) {
            $validated['remaining_amount'] = $validated['total_amount'] - $validated['advance_payment'];
        } else {
            $validated['remaining_amount'] = $validated['total_amount'];
        }

        AccountingRecord::create($validated);

        return redirect()->route('processing.accounting.index')
            ->with('success', 'Registro contable creado exitosamente');
    }

    public function show(AccountingRecord $accounting)
    {
        $accounting->load(['supplier', 'contract']);
        return view('processing.accounting.show', compact('accounting'));
    }

    public function edit(AccountingRecord $accounting)
    {
        $suppliers = Supplier::all();
        $contracts = Contract::whereIn('status', ['active', 'completed'])->get();
        
        return view('processing.accounting.edit', compact('accounting', 'suppliers', 'contracts'));
    }

    public function update(Request $request, AccountingRecord $accounting)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'transaction_type' => 'required|in:purchase,sale,payment,advance',
            'transaction_date' => 'required|date',
            'closing_date' => 'nullable|date',
            'product_description' => 'nullable|string',
            'size_range' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
            'quantity_kg' => 'required|numeric|min:0',
            'currency' => 'required|in:USD,CLP',
            'exchange_rate' => 'nullable|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'payment_due_date' => 'nullable|date',
            'actual_payment_date' => 'nullable|date',
            'payment_status' => 'required|in:pending,partial,paid',
            'notes' => 'nullable|string',
        ]);

        // Recalcular monto total
        $validated['total_amount'] = $validated['price_per_kg'] * $validated['quantity_kg'];
        
        // Recalcular monto restante
        if (isset($validated['advance_payment'])) {
            $validated['remaining_amount'] = $validated['total_amount'] - $validated['advance_payment'];
        } else {
            $validated['remaining_amount'] = $validated['total_amount'];
        }

        $accounting->update($validated);

        return redirect()->route('processing.accounting.index')
            ->with('success', 'Registro contable actualizado exitosamente');
    }

    public function destroy(AccountingRecord $accounting)
    {
        $accounting->delete();
        return redirect()->route('processing.accounting.index')
            ->with('success', 'Registro contable eliminado exitosamente');
    }

    public function dashboard()
    {
        // Vista de dashboard con gráficos y resúmenes
        return view('processing.accounting.dashboard');
    }
}
