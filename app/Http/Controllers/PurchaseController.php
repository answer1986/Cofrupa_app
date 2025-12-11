<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Bin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'bins'])
            ->orderBy('purchase_date', 'desc')
            ->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $bins = Bin::where('status', 'available')->orderBy('bin_number')->get();

        return view('purchases.create', compact('suppliers', 'bins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_order' => 'nullable|string|max:50',
            'bin_ids' => 'required|array|min:1',
            'bin_ids.*' => 'exists:bins,id',
            'purchase_date' => 'required|date',
            'weight_purchased' => 'required|numeric|min:0',
            'calibre' => [
                'required',
                Rule::in([
                    '80-90', '120-x', '90-100', '70-90',
                    'Grande 50-60', 'Mediana 40-50', 'Pequeña 30-40'
                ])
            ],
            'units_per_pound' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_due_date' => 'nullable|date|after:purchase_date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Calculate total amount and owed amount
        $totalAmount = $request->unit_price ? $request->unit_price * $request->weight_purchased : 0;
        $amountOwed = $totalAmount - ($request->amount_paid ?? 0);

        // Determine payment status
        $paymentStatus = 'pending';
        if ($request->amount_paid > 0) {
            if ($request->amount_paid >= $totalAmount) {
                $paymentStatus = 'paid';
            } else {
                $paymentStatus = 'partial';
            }
        }

        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'purchase_order' => $request->purchase_order,
            'purchase_date' => $request->purchase_date,
            'weight_purchased' => $request->weight_purchased,
            'calibre' => $request->calibre,
            'units_per_pound' => $request->units_per_pound,
            'unit_price' => $request->unit_price,
            'total_amount' => $totalAmount,
            'amount_paid' => $request->amount_paid ?? 0,
            'amount_owed' => $amountOwed,
            'payment_status' => $paymentStatus,
            'payment_due_date' => $request->payment_due_date,
            'notes' => $request->notes,
        ]);

        // Attach bins to purchase using pivot table and update their status
        $bins = Bin::whereIn('id', $request->bin_ids)->get();
        $weightPerBin = $request->weight_purchased / count($bins);

        foreach ($bins as $bin) {
            // Attach bin to purchase in pivot table
            $purchase->bins()->attach($bin->id);

            // Update bin status (weight is calculated dynamically from purchases)
            $bin->supplier_id = $request->supplier_id;
            $bin->status = 'in_use';
            $bin->save();
        }

        // Update supplier debt
        $supplier = Supplier::find($request->supplier_id);
        $supplier->total_debt += $amountOwed;
        $supplier->total_paid += $request->amount_paid ?? 0;
        $supplier->save();

        return redirect()->route('purchases.index')->with('success', 'Compra registrada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'bins.purchases'])->findOrFail($id);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $suppliers = Supplier::orderBy('name')->get();
        $bins = Bin::orderBy('bin_number')->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'bins'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_order' => 'nullable|string|max:255',
            'purchase_date' => 'required|date',
            'weight_purchased' => 'required|numeric|min:0',
            'calibre' => 'required|in:80-90,120-x,90-100,70-90,Grande 50-60,Mediana 40-50,Pequeña 30-40',
            'units_per_pound' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_due_date' => 'nullable|date|after:today',
            'bin_ids' => 'required|array|min:1',
            'bin_ids.*' => 'exists:bins,id',
            'notes' => 'nullable|string',
        ]);

        $purchase = Purchase::findOrFail($id);

        // Calculate total amount if not provided
        $totalAmount = $request->total_amount;
        if (!$totalAmount && $request->unit_price) {
            $totalAmount = $request->unit_price * $request->weight_purchased;
        }

        // Calculate owed amount
        $amountOwed = $totalAmount - ($request->amount_paid ?? 0);

        // Determine payment status
        $paymentStatus = 'pending';
        if (($request->amount_paid ?? 0) >= $totalAmount) {
            $paymentStatus = 'paid';
        } elseif (($request->amount_paid ?? 0) > 0) {
            $paymentStatus = 'partial';
        }

        // Update purchase
        $purchase->update([
            'supplier_id' => $request->supplier_id,
            'purchase_order' => $request->purchase_order,
            'purchase_date' => $request->purchase_date,
            'weight_purchased' => $request->weight_purchased,
            'calibre' => $request->calibre,
            'units_per_pound' => $request->units_per_pound,
            'unit_price' => $request->unit_price,
            'total_amount' => $totalAmount,
            'amount_paid' => $request->amount_paid ?? 0,
            'amount_owed' => $amountOwed,
            'payment_status' => $paymentStatus,
            'payment_due_date' => $request->payment_due_date,
            'notes' => $request->notes,
        ]);

        // Update financial tracking for supplier
        $supplier = $purchase->supplier;
        $supplier->total_debt = $supplier->purchases->sum('amount_owed');
        $supplier->total_paid = $supplier->purchases->sum('amount_paid');
        $supplier->save();

        // Handle bin changes
        $oldBins = $purchase->bins;
        $newBins = Bin::whereIn('id', $request->bin_ids)->get();

        // Detach old bins and update their status
        foreach ($oldBins as $oldBin) {
            $purchase->bins()->detach($oldBin->id);

            // Check if this bin is still used by other purchases
            $stillUsed = Purchase::whereHas('bins', function($query) use ($oldBin) {
                $query->where('bin_id', $oldBin->id);
            })->exists();

            if (!$stillUsed) {
                // Weight is calculated dynamically, no need to set to 0
                $oldBin->status = 'available';
                $oldBin->supplier_id = null;
                $oldBin->save();
            }
        }

        // Attach new bins and update their status
        $weightPerBin = $request->weight_purchased / count($newBins);

        foreach ($newBins as $newBin) {
            $purchase->bins()->attach($newBin->id);

            // Weight is calculated dynamically from purchases
            $newBin->supplier_id = $request->supplier_id;
            $newBin->status = 'in_use';
            $newBin->save();
        }

        return redirect()->route('purchases.show', $purchase)->with('success', 'Compra actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);

        // Reverse the financial updates
        $supplier = $purchase->supplier;
        $supplier->total_debt -= $purchase->amount_owed;
        $supplier->total_paid -= $purchase->amount_paid;
        $supplier->save();

        // Update bins weight and status (for multiple bins)
        $bins = $purchase->bins;
        $weightPerBin = $purchase->weight_purchased / $bins->count();

        foreach ($bins as $bin) {
            // Check if bin has other purchases
            $hasOtherPurchases = $bin->purchases()->where('purchases.id', '!=', $purchase->id)->exists();
            
            if (!$hasOtherPurchases) {
                // Weight is calculated dynamically, no need to update
                $bin->status = 'available';
                $bin->supplier_id = null;
            }
            $bin->save();
        }

        // Detach all bins from the purchase (removes pivot table entries)
        $purchase->bins()->detach();

        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Compra eliminada exitosamente.');
    }
}
