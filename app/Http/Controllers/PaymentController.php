<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\FinancePurchase;
use App\Models\FinanceSale;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['payable', 'creator']);

        // Filtros
        if ($request->filled('company')) {
            $query->where('company', $request->company);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }
        if ($request->filled('date_from')) {
            $query->where('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest('payment_date')->paginate(30);

        // Estadísticas para cards
        $totalPaid = Payment::where('status', 'completado')->sum('amount');
        $totalPending = Payment::where('status', 'pendiente')->sum('amount');
        $paymentsByMethod = Payment::where('status', 'completado')
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        return view('finance.payments.index', compact('payments', 'totalPaid', 'totalPending', 'paymentsByMethod'));
    }

    public function create(Request $request)
    {
        $company = $request->get('company', 'cofrupa');
        
        // Listas para selects
        $suppliers = \App\Models\Supplier::orderBy('name')->pluck('name')->unique()->values();
        $clients = \App\Models\Client::orderBy('name')->pluck('name')->unique()->values();

        return view('finance.payments.create', compact('company', 'suppliers', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|in:cofrupa,luis_gonzalez,comercializadora',
            'payment_method' => 'required|in:cheque,transferencia,efectivo,tarjeta,otro',
            'reference_number' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:CLP,USD',
            'payment_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'purchase_order' => 'nullable|string|max:255',
            'payee_name' => 'nullable|string|max:255',
            'payment_type' => 'required|in:compra,venta,gasto,otro',
            'payable_id' => 'nullable|integer',
            'payable_type' => 'nullable|string|max:255',
            'status' => 'required|in:pendiente,completado,rechazado,anulado',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        Payment::create($validated);

        return redirect()->route('finance.payments.index')
            ->with('success', 'Pago registrado exitosamente');
    }

    public function show(Payment $payment)
    {
        $payment->load(['payable', 'creator']);
        return view('finance.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $suppliers = \App\Models\Supplier::orderBy('name')->pluck('name')->unique()->values();
        $clients = \App\Models\Client::orderBy('name')->pluck('name')->unique()->values();

        return view('finance.payments.edit', compact('payment', 'suppliers', 'clients'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'company' => 'required|in:cofrupa,luis_gonzalez,comercializadora',
            'payment_method' => 'required|in:cheque,transferencia,efectivo,tarjeta,otro',
            'reference_number' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:CLP,USD',
            'payment_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'purchase_order' => 'nullable|string|max:255',
            'payee_name' => 'nullable|string|max:255',
            'payment_type' => 'required|in:compra,venta,gasto,otro',
            'payable_id' => 'nullable|integer',
            'payable_type' => 'nullable|string|max:255',
            'status' => 'required|in:pendiente,completado,rechazado,anulado',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('finance.payments.index')
            ->with('success', 'Pago actualizado exitosamente');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('finance.payments.index')
            ->with('success', 'Pago eliminado exitosamente');
    }
}
