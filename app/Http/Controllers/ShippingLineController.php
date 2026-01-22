<?php

namespace App\Http\Controllers;

use App\Models\ShippingLine;
use Illuminate\Http\Request;

class ShippingLineController extends Controller
{
    public function index()
    {
        $shippingLines = ShippingLine::with('shipments')->paginate(15);
        return view('shipping-lines.index', compact('shippingLines'));
    }

    public function create()
    {
        return view('shipping-lines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:shipping_lines,code',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        ShippingLine::create($validated);

        return redirect()->route('shipping-lines.index')->with('success', 'Naviera creada exitosamente.');
    }

    public function show($id)
    {
        $shippingLine = ShippingLine::with('shipments.contract.client')->findOrFail($id);
        return view('shipping-lines.show', compact('shippingLine'));
    }

    public function edit($id)
    {
        $shippingLine = ShippingLine::findOrFail($id);
        return view('shipping-lines.edit', compact('shippingLine'));
    }

    public function update(Request $request, $id)
    {
        $shippingLine = ShippingLine::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:shipping_lines,code,' . $id,
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $shippingLine->update($validated);

        return redirect()->route('shipping-lines.index')->with('success', 'Naviera actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $shippingLine = ShippingLine::findOrFail($id);
        $shippingLine->delete();

        return redirect()->route('shipping-lines.index')->with('success', 'Naviera eliminada exitosamente.');
    }
}
