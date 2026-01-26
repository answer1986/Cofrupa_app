<?php

namespace App\Http\Controllers;

use App\Models\CustomsAgency;
use Illuminate\Http\Request;

class CustomsAgencyController extends Controller
{
    public function index()
    {
        $agencies = CustomsAgency::with('contacts')->paginate(15);
        return view('customs-agencies.index', compact('agencies'));
    }

    public function create()
    {
        return view('customs-agencies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:customs_agencies,code',
            'address' => 'nullable|string',
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

        $agency = CustomsAgency::create($validated);

        if ($request->has('contacts')) {
            foreach ($request->contacts as $index => $contact) {
                if (!empty($contact['contact_person']) || !empty($contact['phone']) || !empty($contact['email'])) {
                    $agency->contacts()->create([
                        'contact_person' => $contact['contact_person'] ?? null,
                        'phone' => $contact['phone'] ?? null,
                        'email' => $contact['email'] ?? null,
                        'position' => $contact['position'] ?? null,
                        'order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('customs-agencies.index')->with('success', 'Agencia de aduana creada exitosamente.');
    }

    public function show($id)
    {
        $agency = CustomsAgency::with('contacts')->findOrFail($id);
        return view('customs-agencies.show', compact('agency'));
    }

    public function edit($id)
    {
        $agency = CustomsAgency::with('contacts')->findOrFail($id);
        return view('customs-agencies.edit', compact('agency'));
    }

    public function update(Request $request, $id)
    {
        $agency = CustomsAgency::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:customs_agencies,code,' . $id,
            'address' => 'nullable|string',
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

        $agency->update($validated);

        // Eliminar contactos existentes y crear nuevos
        $agency->contacts()->delete();
        if ($request->has('contacts')) {
            foreach ($request->contacts as $index => $contact) {
                if (!empty($contact['contact_person']) || !empty($contact['phone']) || !empty($contact['email'])) {
                    $agency->contacts()->create([
                        'contact_person' => $contact['contact_person'] ?? null,
                        'phone' => $contact['phone'] ?? null,
                        'email' => $contact['email'] ?? null,
                        'position' => $contact['position'] ?? null,
                        'order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('customs-agencies.index')->with('success', 'Agencia de aduana actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $agency = CustomsAgency::findOrFail($id);
        $agency->delete();

        return redirect()->route('customs-agencies.index')->with('success', 'Agencia de aduana eliminada exitosamente.');
    }
}
