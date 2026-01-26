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
            'is_active' => 'boolean',
            'tax_id' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_type' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'contacts' => 'nullable|array|max:5',
            'contacts.*.contact_person' => 'nullable|string|max:255',
            'contacts.*.phone' => 'nullable|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.position' => 'nullable|string|max:255',
        ]);

        $shippingLine = ShippingLine::create($validated);

        if ($request->has('contacts')) {
            foreach ($request->contacts as $index => $contact) {
                if (!empty($contact['contact_person']) || !empty($contact['phone']) || !empty($contact['email'])) {
                    $shippingLine->contacts()->create([
                        'contact_person' => $contact['contact_person'] ?? null,
                        'phone' => $contact['phone'] ?? null,
                        'email' => $contact['email'] ?? null,
                        'position' => $contact['position'] ?? null,
                        'order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('shipping-lines.index')->with('success', 'Naviera creada exitosamente.');
    }

    public function show($id)
    {
        $shippingLine = ShippingLine::with('shipments.contract.client')->findOrFail($id);
        return view('shipping-lines.show', compact('shippingLine'));
    }

    public function edit($id)
    {
        $shippingLine = ShippingLine::with('contacts')->findOrFail($id);
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
            'tax_id' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_type' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'contacts' => 'nullable|array|max:5',
            'contacts.*.contact_person' => 'nullable|string|max:255',
            'contacts.*.phone' => 'nullable|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.position' => 'nullable|string|max:255',
        ]);

        $shippingLine->update($validated);

        // Eliminar contactos existentes y crear nuevos
        $shippingLine->contacts()->delete();
        if ($request->has('contacts')) {
            foreach ($request->contacts as $index => $contact) {
                if (!empty($contact['contact_person']) || !empty($contact['phone']) || !empty($contact['email'])) {
                    $shippingLine->contacts()->create([
                        'contact_person' => $contact['contact_person'] ?? null,
                        'phone' => $contact['phone'] ?? null,
                        'email' => $contact['email'] ?? null,
                        'position' => $contact['position'] ?? null,
                        'order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('shipping-lines.index')->with('success', 'Naviera actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $shippingLine = ShippingLine::findOrFail($id);
        $shippingLine->delete();

        return redirect()->route('shipping-lines.index')->with('success', 'Naviera eliminada exitosamente.');
    }
}
