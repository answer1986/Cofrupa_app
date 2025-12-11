<?php

namespace App\Http\Controllers;

use App\Models\Bin;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bins = Bin::with(['supplier', 'purchases'])->paginate(15);
        return view('bins.index', compact('bins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('bins.create', compact('suppliers'));
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
            'bin_number' => 'required|string|max:50|unique:bins',
            'type' => ['required', Rule::in(['wood', 'plastic'])],
            'ownership_type' => ['required', Rule::in(['supplier', 'internal', 'field'])],
            'weight_capacity' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'status' => ['required', Rule::in(['available', 'in_use', 'maintenance', 'damaged'])],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delivery_date' => 'nullable|date',
            'return_date' => 'nullable|date|after_or_equal:delivery_date',
            'damage_description' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500',
        ]);

        $data = $request->all();

        // Weight capacity is now the empty bin weight (tare), set by user
        // Default to empty bin weight if not provided
        if (empty($data['weight_capacity'])) {
            $data['weight_capacity'] = Bin::getEmptyBinWeight($request->type);
        }
        $data['current_weight'] = 0; // Will be calculated from purchases

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('bins', 'public');
            $data['photo_path'] = $photoPath;
        }

        Bin::create($data);

        return redirect()->route('bins.index')->with('success', 'Bin creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bin = Bin::with('supplier')->findOrFail($id);
        return view('bins.show', compact('bin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bin = Bin::findOrFail($id);
        $suppliers = Supplier::orderBy('name')->get();
        return view('bins.edit', compact('bin', 'suppliers'));
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
        $bin = Bin::findOrFail($id);

        $request->validate([
            'bin_number' => ['required', 'string', 'max:50', Rule::unique('bins')->ignore($bin->id)],
            'type' => ['required', Rule::in(['wood', 'plastic'])],
            'ownership_type' => ['required', Rule::in(['supplier', 'internal', 'field'])],
            'weight_capacity' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'status' => ['required', Rule::in(['available', 'in_use', 'maintenance', 'damaged'])],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delivery_date' => 'nullable|date',
            'return_date' => 'nullable|date|after_or_equal:delivery_date',
            'damage_description' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500',
        ]);

        $data = $request->all();
        
        // Weight capacity is the empty bin weight (tare), set by user
        // Don't update current_weight as it's calculated from purchases

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($bin->photo_path) {
                Storage::disk('public')->delete($bin->photo_path);
            }

            $photoPath = $request->file('photo')->store('bins', 'public');
            $data['photo_path'] = $photoPath;
        }

        $bin->update($data);

        return redirect()->route('bins.index')->with('success', 'Bin actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bin = Bin::findOrFail($id);

        // Check if bin has current weight or is assigned to supplier
        if ($bin->current_weight > 0 || $bin->supplier_id) {
            return redirect()->route('bins.index')->with('error', 'No se puede eliminar el bin porque tiene peso actual o está asignado a un proveedor.');
        }

        // Delete photo if exists
        if ($bin->photo_path) {
            Storage::disk('public')->delete($bin->photo_path);
        }

        $bin->delete();

        return redirect()->route('bins.index')->with('success', 'Bin eliminado exitosamente.');
    }

    /**
     * Mark bin as returned from supplier
     */
    public function returnBin($id)
    {
        $bin = Bin::findOrFail($id);

        // Find the current assignment and mark it as returned
        $currentAssignment = $bin->currentAssignment;
        if ($currentAssignment) {
            $currentAssignment->update([
                'return_date' => now(),
                'weight_returned' => $bin->current_weight,
            ]);
        }

        // Update bin status
        $bin->update([
            'supplier_id' => null,
            'return_date' => now(),
            'status' => 'available',
            'current_weight' => 0,
        ]);

        return redirect()->route('bins.show', $bin)->with('success', 'Bin marcado como devuelto.');
    }

    /**
     * Assign bin to supplier
     */
    public function assignToSupplier(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'delivery_date' => 'required|date',
        ]);

        $bin = Bin::findOrFail($id);

        // Create assignment record
        \App\Models\BinAssignment::create([
            'bin_id' => $bin->id,
            'supplier_id' => $request->supplier_id,
            'delivery_date' => $request->delivery_date,
            'weight_delivered' => $bin->current_weight,
        ]);

        // Update bin status
        $bin->update([
            'supplier_id' => $request->supplier_id,
            'delivery_date' => $request->delivery_date,
            'return_date' => null,
            'status' => 'in_use',
        ]);

        return redirect()->route('bins.show', $bin)->with('success', 'Bin asignado al proveedor exitosamente.');
    }
}
