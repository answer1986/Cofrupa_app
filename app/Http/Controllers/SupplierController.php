<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::with('bins')->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suppliers.create');
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
            'name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'csg_code' => 'nullable|string|max:255',
            'internal_code' => 'nullable|string|max:255|unique:suppliers,internal_code',
            'location' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'business_type' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_type' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        // Generar código interno automáticamente si no se proporciona
        if (empty($data['internal_code'])) {
            $data['internal_code'] = $this->generateInternalCode();
        }

        Supplier::create($data);

        return redirect()->route('suppliers.index')->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Generate a unique internal code for supplier
     *
     * @return string
     */
    private function generateInternalCode()
    {
        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        $nextNumber = $lastSupplier ? $lastSupplier->id + 1 : 1;
        
        // Formato: PROV-0001, PROV-0002, etc.
        $code = 'PROV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        // Verificar que el código sea único
        while (Supplier::where('internal_code', $code)->exists()) {
            $nextNumber++;
            $code = 'PROV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }
        
        return $code;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::with(['bins', 'purchases'])->findOrFail($id);
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
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
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'csg_code' => 'nullable|string|max:255',
            'internal_code' => 'nullable|string|max:255|unique:suppliers,internal_code,' . $id,
            'location' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'business_type' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_type' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        // Si no hay código interno, generar uno automáticamente
        if (empty($data['internal_code'])) {
            $data['internal_code'] = $this->generateInternalCode();
        }

        $supplier->update($data);

        return redirect()->route('suppliers.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Check if supplier has bins or purchases
        if ($supplier->bins()->count() > 0 || $supplier->purchases()->count() > 0) {
            return redirect()->route('suppliers.index')->with('error', 'No se puede eliminar el proveedor porque tiene bins o compras asociadas.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
