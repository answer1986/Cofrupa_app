<?php

namespace App\Http\Controllers;

use App\Models\FinanceBankDebt;
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
        $tab = $request->get('tab', 'dashboard'); // Default to dashboard

        if ($tab === 'dashboard') {
            // Dashboard Data Logic
            // 1. Deuda/Capital por banco (registro aparte, para comprar y vender)
            $debtsByBank = FinanceBankDebt::where('company', $company)
                ->orderBy('bank')
                ->orderBy('due_date')
                ->get();

            // 2. Main Table Data (Recent Purchases)
            // Matching columns: Date, Invoice, Supplier, Caliber, Type, Kilos, T/C, Unit $, Unit US$, Total Net $, Total Net US$, IVA, Total $, Total US$, Balance
            $records = FinancePurchase::where('company', $company)
                ->orderBy('purchase_date', 'desc')
                ->paginate(50); // Larger pagination for spreadsheet view

            return view('finance.index', compact('company', 'tab', 'debtsByBank', 'records'));
        }

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
        
        // Lista de proveedores para autocompletado
        $suppliers = \App\Models\Supplier::orderBy('name')->pluck('name')->unique()->values();
        
        // Lista de bancos comunes en Chile
        $banks = [
            'Banco de Chile',
            'Banco Estado',
            'Banco Santander',
            'Banco BCI',
            'Banco Scotiabank',
            'Banco Itaú',
            'Banco BICE',
            'Banco Security',
            'Banco Falabella',
            'Banco Ripley',
            'Otro'
        ];
        
        return view('finance.purchases.create', compact('company', 'suppliers', 'banks'));
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
            'bank' => 'nullable|string|max:255',
            'exchange_rate' => 'nullable|numeric|min:0',
        ]);

        // Auto-Calculation Logic
        $kilos = $validated['kilos'];
        $unitPriceClp = $validated['unit_price_clp'] ?? 0;
        $unitPriceUsd = $validated['unit_price_usd'] ?? 0;
        $exchangeRate = $validated['exchange_rate'] ?? null;

        // 1. Calculate Unit Prices if Exchange Rate is present
        if ($exchangeRate > 0) {
            if ($unitPriceClp > 0 && $unitPriceUsd == 0) {
                $unitPriceUsd = $unitPriceClp / $exchangeRate;
                $validated['unit_price_usd'] = $unitPriceUsd;
            } elseif ($unitPriceUsd > 0 && $unitPriceClp == 0) {
                $unitPriceClp = $unitPriceUsd * $exchangeRate;
                $validated['unit_price_clp'] = $unitPriceClp;
            }
        }

        // 2. Calculate Net Totals
        $validated['total_net_clp'] = $kilos * $unitPriceClp;
        $validated['total_net_usd'] = $kilos * $unitPriceUsd;

        // 3. Calculate IVA
        if ($request->has('with_iva') && $request->with_iva) {
            $validated['iva'] = $validated['total_net_clp'] * 0.19;
        } else {
            $validated['iva'] = 0;
        }

        // 4. Calculate Totals (Net + IVA)
        $validated['total_clp'] = $validated['total_net_clp'] + $validated['iva'];
        $validated['total_usd'] = $validated['total_net_usd']; // IVA usually doesn't apply to USD total in this context, or is separate. Assuming simple logic for now.

        // 5. Final Total (Total + Other Costs)
        $otherCosts = $validated['other_costs'] ?? 0;
        $validated['final_total'] = $validated['total_clp'] + $otherCosts; // Defaulting final total to CLP basis? Or depends on currency?
        // Let's assume final_total is the display total, usually in CLP for local accounting
        
        FinancePurchase::create($validated);

        return redirect()->route('finance.index', ['company' => $validated['company'], 'tab' => 'purchases'])
            ->with('success', 'Compra registrada exitosamente');
    }

    public function editPurchase(FinancePurchase $purchase)
    {
        // Lista de proveedores para autocompletado
        $suppliers = \App\Models\Supplier::orderBy('name')->pluck('name')->unique()->values();
        
        // Lista de bancos comunes en Chile
        $banks = [
            'Banco de Chile',
            'Banco Estado',
            'Banco Santander',
            'Banco BCI',
            'Banco Scotiabank',
            'Banco Itaú',
            'Banco BICE',
            'Banco Security',
            'Banco Falabella',
            'Banco Ripley',
            'Otro'
        ];
        
        return view('finance.purchases.edit', compact('purchase', 'suppliers', 'banks'));
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
            'bank' => 'nullable|string|max:255',
            'exchange_rate' => 'nullable|numeric|min:0',
        ]);

        // Auto-Calculation Logic (Same as store)
        $kilos = $validated['kilos'];
        $unitPriceClp = $validated['unit_price_clp'] ?? 0;
        $unitPriceUsd = $validated['unit_price_usd'] ?? 0;
        $exchangeRate = $validated['exchange_rate'] ?? null;

        if ($exchangeRate > 0) {
            if ($unitPriceClp > 0 && $unitPriceUsd == 0) {
                $unitPriceUsd = $unitPriceClp / $exchangeRate;
                $validated['unit_price_usd'] = $unitPriceUsd;
            } elseif ($unitPriceUsd > 0 && $unitPriceClp == 0) {
                $unitPriceClp = $unitPriceUsd * $exchangeRate;
                $validated['unit_price_clp'] = $unitPriceClp;
            }
        }

        $validated['total_net_clp'] = $kilos * $unitPriceClp;
        $validated['total_net_usd'] = $kilos * $unitPriceUsd;

        if ($request->has('with_iva') && $request->with_iva) {
            $validated['iva'] = $validated['total_net_clp'] * 0.19;
        } else {
            $validated['iva'] = 0;
        }

        $validated['total_clp'] = $validated['total_net_clp'] + $validated['iva'];
        $validated['total_usd'] = $validated['total_net_usd'];

        $otherCosts = $validated['other_costs'] ?? 0;
        $validated['final_total'] = $validated['total_clp'] + $otherCosts;

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

    // === DEUDA/CAPITAL POR BANCO (registro aparte) ===
    public function createBankDebt(Request $request)
    {
        $company = $request->get('company', 'cofrupa');
        return view('finance.bank-debts.create', compact('company'));
    }

    public function storeBankDebt(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|in:cofrupa,luis_gonzalez,comercializadora',
            'bank' => 'required|string|max:255',
            'amount_usd' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'type' => 'required|in:compra,venta,general',
            'notes' => 'nullable|string',
        ]);

        FinanceBankDebt::create($validated);

        return redirect()->route('finance.index', ['company' => $validated['company'], 'tab' => 'dashboard'])
            ->with('success', 'Deuda/capital por banco registrada exitosamente');
    }

    public function editBankDebt(FinanceBankDebt $bankDebt)
    {
        return view('finance.bank-debts.edit', compact('bankDebt'));
    }

    public function updateBankDebt(Request $request, FinanceBankDebt $bankDebt)
    {
        $validated = $request->validate([
            'company' => 'required|in:cofrupa,luis_gonzalez,comercializadora',
            'bank' => 'required|string|max:255',
            'amount_usd' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'type' => 'required|in:compra,venta,general',
            'notes' => 'nullable|string',
        ]);

        $bankDebt->update($validated);

        return redirect()->route('finance.index', ['company' => $bankDebt->company, 'tab' => 'dashboard'])
            ->with('success', 'Deuda/capital por banco actualizada exitosamente');
    }

    public function destroyBankDebt(FinanceBankDebt $bankDebt)
    {
        $company = $bankDebt->company;
        $bankDebt->delete();
        return redirect()->route('finance.index', ['company' => $company, 'tab' => 'dashboard'])
            ->with('success', 'Deuda/capital por banco eliminada exitosamente');
    }
}
