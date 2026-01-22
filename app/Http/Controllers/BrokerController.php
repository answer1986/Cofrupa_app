<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\BrokerPayment;
use Illuminate\Http\Request;

class BrokerController extends Controller
{
    public function index()
    {
        $brokers = Broker::with(['payments', 'contracts'])->paginate(15);
        return view('brokers.index', compact('brokers'));
    }

    public function create()
    {
        return view('brokers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'commission_percentage' => 'required|numeric|min:1.5|max:3.0',
            'email' => 'nullable|email:rfc,dns|max:255',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\+[0-9]{11}$/'],
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account_type' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
        ], [
            'phone.regex' => 'El teléfono debe comenzar con + seguido de exactamente 11 números (ejemplo: +56912345678)',
            'email.email' => 'El formato del correo electrónico no es válido',
        ]);

        Broker::create($request->all());

        return redirect()->route('brokers.index')->with('success', 'Broker creado exitosamente.');
    }

    public function show($id)
    {
        $broker = Broker::with(['payments.contract', 'contracts.client'])->findOrFail($id);
        return view('brokers.show', compact('broker'));
    }

    public function edit($id)
    {
        $broker = Broker::findOrFail($id);
        return view('brokers.edit', compact('broker'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'commission_percentage' => 'required|numeric|min:1.5|max:3.0',
            'email' => 'nullable|email:rfc,dns|max:255',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\+[0-9]{11}$/'],
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_account_type' => 'nullable|string',
            'bank_account_number' => 'nullable|string',
        ], [
            'phone.regex' => 'El teléfono debe comenzar con + seguido de exactamente 11 números (ejemplo: +56912345678)',
            'email.email' => 'El formato del correo electrónico no es válido',
        ]);

        $broker = Broker::findOrFail($id);
        $broker->update($request->all());

        return redirect()->route('brokers.index')->with('success', 'Broker actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $broker = Broker::findOrFail($id);
        $broker->delete();

        return redirect()->route('brokers.index')->with('success', 'Broker eliminado exitosamente.');
    }
}
