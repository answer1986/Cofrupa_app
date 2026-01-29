<?php

namespace App\Http\Controllers;

use App\Models\FinancePurchase;
use App\Models\FinanceSale;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Vista principal: pestañas por empresa (Cofrupa, Luis Gonzalez, Comercializadora)
     * con sub-pestañas Compras y Ventas
     */
    public function index(Request $request)
    {
        $company = $request->get('company', 'cofrupa');
        $tab = $request->get('tab', 'purchases'); // purchases o sales

        $query = $tab === 'purchases' 
            ? FinancePurchase::where('company', $company)
            : FinanceSale::where('company', $company);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $dateField = $tab === 'purchases' ? 'purchase_date' : 'sale_date';
            $query->where($dateField, '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $dateField = $tab === 'purchases' ? 'purchase_date' : 'sale_date';
            $query->where($dateField, '<=', $request->date_to);
        }

        $dateField = $tab === 'purchases' ? 'purchase_date' : 'sale_date';
        $records = $query->orderBy($dateField, 'desc')->paginate(20)->withQueryString();

        // Estadísticas
        $totalKilos = $query->sum('kilos');
        $totalAmount = $tab === 'purchases' 
            ? FinancePurchase::where('company', $company)->sum('final_total')
            : FinanceSale::where('company', $company)->sum('total_sale_usd');

        return view('finance.index', compact('records', 'company', 'tab', 'totalKilos', 'totalAmount'));
    }

    // === COMPRAS ===
    public function createPurchase(Request $request)
    {
        $company = $request->get('company', 'cofrupa');
        return view('finance.purchases.create', compact('company'));
    }

    public function storePurchase(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|in:cofrupa,luis_gonzalez,comercializadora',
            'purchase_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'supplier_name' => 'required|string|max:255',
            'product_caliber' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'kilos' => 'required|numeric|min:0',
            'unit_price_clp' => 'nullable|numeric|min:0',
            'unit_price_usd' => 'nullable|numeric|min:0',
            'total_net_clp' => 'nullable|numeric|min:0',
            'total_net_usd' => 'nullable|numeric|min:0',
            'iva' => 'nullable|numeric|min:0',
            'total_clp' => 'nullable|numeric|min:0',
            'total_usd' => 'nullable|numeric|min:0',
            'commission_per_kilo' => 'nullable|numeric|min:0',
            'total_commission' => 'nullable|numeric|min:0',
            'freight_per_kilo' => 'nullable|numeric|min:0',
            'total_freight' => 'nullable|numeric|min:0',
            'other_costs' => 'nullable|numeric|min:0',
            'final_total' => 'nullable|numeric|min:0',
            'average_per_kilo' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,paid,partial,cancelled',
            'with_iva' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        FinancePurchase::create($validated);

        return redirect()->route('finance.index', ['company' => $validated['company'], 'tab' => 'purchases'])
            ->with('success', 'Compra registrada exitosamente');
    }

    public function editPurchase(FinancePurchase $purchase)
    {
        return view('finance.purchases.edit', compact('purchase'));
    }

    public function updatePurchase(Request $request, FinancePurchase $purchase)
    {
        $validated = $request->validate([
            'company' => 'required|in:cofrupa,luis_gonzalez,comercializadora',
            'purchase_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'supplier_name' => 'required|string|max:255',
            'product_caliber' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'kilos' => 'required|numeric|min:0',
            'unit_price_clp' => 'nullable|numeric|min:0',
            'unit_price_usd' => 'nullable|numeric|min:0',
            'total_net_clp' => 'nullable|numeric|min:0',
            'total_net_usd' => 'nullable|numeric|min:0',
            'iva' => 'nullable|numeric|min:0',
            'total_clp' => 'nullable|numeric|min:0',
            'total_usd' => 'nullable|numeric|min:0',
            'commission_per_kilo' => 'nullable|numeric|min:0',
            'total_commission' => 'nullable|numeric|min:0',
            'freight_per_kilo' => 'nullable|numeric|min:0',
            'total_freight' => 'nullable|numeric|min:0',
            'other_costs' => 'nullable|numeric|min:0',
            'final_total' => 'nullable|numeric|min:0',
            'average_per_kilo' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,paid,partial,cancelled',
            'with_iva' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $purchase->update($validated);

        return redirect()->route('finance.index', ['company' => $validated['company'], 'tab' => 'purchases'])
            ->with('success', 'Compra actualizada exitosamente');
    }

    public function destroyPurchase(FinancePurchase $purchase)
    {
        $company = $purchase->company;
        $purchase->delete();
        return redirect()->route('finance.index', ['company' => $company, 'tab' => 'purchases'])
            ->with('success', 'Compra eliminada exitosamente');
    }

    // === VENTAS ===
    public function createSale(Request $request)
    {
        $company = $request->get('company', 'cofrupa');
        return view('finance.sales.create', compact('company'));
    }

    public function storeSale(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|in:cofrupa,luis_gonzalez,comercializadora',
            'sale_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'contract_number' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'caliber' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'kilos' => 'required|numeric|min:0',
            'destination_port' => 'nullable|string|max:255',
            'destination_country' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'exchange_rate' => 'nullable|numeric|min:0',
            'unit_price_clp' => 'nullable|numeric|min:0',
            'unit_price_usd' => 'nullable|numeric|min:0',
            'net_price_clp' => 'nullable|numeric|min:0',
            'net_price_usd' => 'nullable|numeric|min:0',
            'total_sale_clp' => 'nullable|numeric|min:0',
            'total_sale_usd' => 'nullable|numeric|min:0',
            'iva_clp' => 'nullable|numeric|min:0',
            'gross_total' => 'nullable|numeric|min:0',
            'payment_usd' => 'nullable|numeric|min:0',
            'balance_usd' => 'nullable|numeric|min:0',
            'paid' => 'boolean',
            'payment_term_days' => 'nullable|integer|min:0',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:pending,paid,partial,cancelled',
            'bank' => 'nullable|string|max:255',
            'with_iva' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        FinanceSale::create($validated);

        return redirect()->route('finance.index', ['company' => $validated['company'], 'tab' => 'sales'])
            ->with('success', 'Venta registrada exitosamente');
    }

    public function editSale(FinanceSale $sale)
    {
        return view('finance.sales.edit', compact('sale'));
    }

    public function updateSale(Request $request, FinanceSale $sale)
    {
        $validated = $request->validate([
            'company' => 'required|in:cofrupa,luis_gonzalez,comercializadora',
            'sale_date' => 'required|date',
            'invoice_number' => 'nullable|string|max:255',
            'contract_number' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'caliber' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'kilos' => 'required|numeric|min:0',
            'destination_port' => 'nullable|string|max:255',
            'destination_country' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'exchange_rate' => 'nullable|numeric|min:0',
            'unit_price_clp' => 'nullable|numeric|min:0',
            'unit_price_usd' => 'nullable|numeric|min:0',
            'net_price_clp' => 'nullable|numeric|min:0',
            'net_price_usd' => 'nullable|numeric|min:0',
            'total_sale_clp' => 'nullable|numeric|min:0',
            'total_sale_usd' => 'nullable|numeric|min:0',
            'iva_clp' => 'nullable|numeric|min:0',
            'gross_total' => 'nullable|numeric|min:0',
            'payment_usd' => 'nullable|numeric|min:0',
            'balance_usd' => 'nullable|numeric|min:0',
            'paid' => 'boolean',
            'payment_term_days' => 'nullable|integer|min:0',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:pending,paid,partial,cancelled',
            'bank' => 'nullable|string|max:255',
            'with_iva' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $sale->update($validated);

        return redirect()->route('finance.index', ['company' => $validated['company'], 'tab' => 'sales'])
            ->with('success', 'Venta actualizada exitosamente');
    }

    public function destroySale(FinanceSale $sale)
    {
        $company = $sale->company;
        $sale->delete();
        return redirect()->route('finance.index', ['company' => $company, 'tab' => 'sales'])
            ->with('success', 'Venta eliminada exitosamente');
    }
}
