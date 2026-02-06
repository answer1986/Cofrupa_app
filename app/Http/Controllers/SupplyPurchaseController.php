<?php

namespace App\Http\Controllers;

use App\Models\SupplyPurchase;
use App\Models\SupplyPurchaseItem;
use Illuminate\Http\Request;

class SupplyPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplyPurchase::with('items');

        // Filtros
        if ($request->filled('supplier_name')) {
            $query->where('supplier_name', 'like', '%' . $request->supplier_name . '%');
        }
        if ($request->filled('buyer')) {
            $query->where('buyer', $request->buyer);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('date_from')) {
            $query->where('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('purchase_date', '<=', $request->date_to);
        }

        $purchases = $query->latest('purchase_date')->paginate(20)->withQueryString();

        return view('supply-purchases.index', compact('purchases'));
    }

    public function create()
    {
        return view('supply-purchases.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'supplier_name' => 'required|string|max:255',
            'invoice_number' => 'nullable|string|max:255',
            'buyer' => 'required|in:LG,Cofrupa,Comercializadora',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.total' => 'nullable|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ], [
            'items.required' => 'Debe agregar al menos un insumo.',
            'items.min' => 'Debe agregar al menos un insumo.',
            'items.*.name.required' => 'El nombre del insumo es obligatorio.',
            'items.*.quantity.required' => 'La cantidad del insumo es obligatoria.',
        ]);

        // Calcular totales
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $itemTotal = $item['total'] ?? ($item['quantity'] * ($item['unit_price'] ?? 0));
            $totalAmount += $itemTotal;
        }

        $amountPaid = $validated['amount_paid'] ?? 0;
        $amountOwed = $totalAmount - $amountPaid;

        // Determinar estado de pago
        $paymentStatus = 'pending';
        if ($amountPaid > 0) {
            if ($amountPaid >= $totalAmount) {
                $paymentStatus = 'paid';
            } else {
                $paymentStatus = 'partial';
            }
        }

        // Crear compra de insumos
        $supplyPurchase = SupplyPurchase::create([
            'purchase_date' => $validated['purchase_date'],
            'supplier_name' => $validated['supplier_name'],
            'invoice_number' => $validated['invoice_number'] ?? null,
            'buyer' => $validated['buyer'],
            'total_amount' => $totalAmount,
            'amount_paid' => $amountPaid,
            'amount_owed' => $amountOwed,
            'payment_status' => $paymentStatus,
            'payment_due_date' => $validated['payment_due_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Crear items
        foreach ($validated['items'] as $itemData) {
            SupplyPurchaseItem::create([
                'supply_purchase_id' => $supplyPurchase->id,
                'name' => $itemData['name'],
                'quantity' => $itemData['quantity'],
                'unit' => $itemData['unit'] ?? 'unidad',
                'unit_price' => $itemData['unit_price'] ?? null,
                'total' => $itemData['total'] ?? ($itemData['quantity'] * ($itemData['unit_price'] ?? 0)),
                'notes' => $itemData['notes'] ?? null,
            ]);
        }

        return redirect()->route('supply-purchases.index')
            ->with('success', 'Compra de insumos registrada exitosamente');
    }

    public function show(SupplyPurchase $supplyPurchase)
    {
        $supplyPurchase->load('items');
        return view('supply-purchases.show', compact('supplyPurchase'));
    }

    public function edit(SupplyPurchase $supplyPurchase)
    {
        $supplyPurchase->load('items');
        return view('supply-purchases.edit', compact('supplyPurchase'));
    }

    public function update(Request $request, SupplyPurchase $supplyPurchase)
    {
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'supplier_name' => 'required|string|max:255',
            'invoice_number' => 'nullable|string|max:255',
            'buyer' => 'required|in:LG,Cofrupa,Comercializadora',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.total' => 'nullable|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        // Calcular totales
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $itemTotal = $item['total'] ?? ($item['quantity'] * ($item['unit_price'] ?? 0));
            $totalAmount += $itemTotal;
        }

        $amountPaid = $validated['amount_paid'] ?? 0;
        $amountOwed = $totalAmount - $amountPaid;

        // Determinar estado de pago
        $paymentStatus = 'pending';
        if ($amountPaid > 0) {
            if ($amountPaid >= $totalAmount) {
                $paymentStatus = 'paid';
            } else {
                $paymentStatus = 'partial';
            }
        }

        // Actualizar compra
        $supplyPurchase->update([
            'purchase_date' => $validated['purchase_date'],
            'supplier_name' => $validated['supplier_name'],
            'invoice_number' => $validated['invoice_number'] ?? null,
            'buyer' => $validated['buyer'],
            'total_amount' => $totalAmount,
            'amount_paid' => $amountPaid,
            'amount_owed' => $amountOwed,
            'payment_status' => $paymentStatus,
            'payment_due_date' => $validated['payment_due_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Eliminar items antiguos y crear nuevos
        $supplyPurchase->items()->delete();
        foreach ($validated['items'] as $itemData) {
            SupplyPurchaseItem::create([
                'supply_purchase_id' => $supplyPurchase->id,
                'name' => $itemData['name'],
                'quantity' => $itemData['quantity'],
                'unit' => $itemData['unit'] ?? 'unidad',
                'unit_price' => $itemData['unit_price'] ?? null,
                'total' => $itemData['total'] ?? ($itemData['quantity'] * ($itemData['unit_price'] ?? 0)),
                'notes' => $itemData['notes'] ?? null,
            ]);
        }

        return redirect()->route('supply-purchases.index')
            ->with('success', 'Compra de insumos actualizada exitosamente');
    }

    public function destroy(SupplyPurchase $supplyPurchase)
    {
        $supplyPurchase->delete();
        return redirect()->route('supply-purchases.index')
            ->with('success', 'Compra de insumos eliminada exitosamente');
    }
}
