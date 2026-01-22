<?php

namespace App\Http\Controllers;

use App\Models\LogisticsCompany;
use Illuminate\Http\Request;

class LogisticsCompanyController extends Controller
{
    public function index()
    {
        $logisticsCompanies = LogisticsCompany::with('shipments')->paginate(15);
        return view('logistics-companies.index', compact('logisticsCompanies'));
    }

    public function create()
    {
        return view('logistics-companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:logistics_companies,code',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email:rfc,dns|max:255',
            'contact_phone' => ['nullable', 'string', 'max:20', 'regex:/^\+[0-9]{11}$/'],
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'contact_phone.regex' => 'El teléfono debe comenzar con + seguido de exactamente 11 números (ejemplo: +56912345678)',
            'contact_email.email' => 'El formato del correo electrónico no es válido',
        ]);

        LogisticsCompany::create($validated);

        return redirect()->route('logistics-companies.index')->with('success', 'Empresa logística creada exitosamente.');
    }

    public function show($id)
    {
        $logisticsCompany = LogisticsCompany::with('shipments.contract.client')->findOrFail($id);
        return view('logistics-companies.show', compact('logisticsCompany'));
    }

    public function edit($id)
    {
        $logisticsCompany = LogisticsCompany::findOrFail($id);
        return view('logistics-companies.edit', compact('logisticsCompany'));
    }

    public function update(Request $request, $id)
    {
        $logisticsCompany = LogisticsCompany::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:logistics_companies,code,' . $id,
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email:rfc,dns|max:255',
            'contact_phone' => ['nullable', 'string', 'max:20', 'regex:/^\+[0-9]{11}$/'],
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'contact_phone.regex' => 'El teléfono debe comenzar con + seguido de exactamente 11 números (ejemplo: +56912345678)',
            'contact_email.email' => 'El formato del correo electrónico no es válido',
        ]);

        $logisticsCompany->update($validated);

        return redirect()->route('logistics-companies.index')->with('success', 'Empresa logística actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $logisticsCompany = LogisticsCompany::findOrFail($id);
        $logisticsCompany->delete();

        return redirect()->route('logistics-companies.index')->with('success', 'Empresa logística eliminada exitosamente.');
    }
}
