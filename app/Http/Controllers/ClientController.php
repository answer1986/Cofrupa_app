<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Broker;
use App\Models\Conversation;
use App\Models\BrokerPayment;
use App\Models\ShippingLine;
use App\Models\LogisticsCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with(['conversations', 'contracts'])->paginate(15);
        $brokers = Broker::with('payments')->paginate(15);
        $conversations = Conversation::with(['client', 'broker', 'user'])->latest()->paginate(15);
        $shippingLines = ShippingLine::with('shipments')->paginate(15);
        $logisticsCompanies = LogisticsCompany::with('shipments')->paginate(15);
        
        return view('clients.index', compact('clients', 'brokers', 'conversations', 'shippingLines', 'logisticsCompanies'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:constant,new',
            'email' => 'nullable|email:rfc,dns|max:255',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\+[0-9]{11}$/'],
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ], [
            'phone.regex' => 'El teléfono debe comenzar con + seguido de exactamente 11 números (ejemplo: +56912345678)',
            'email.email' => 'El formato del correo electrónico no es válido',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function show($id)
    {
        $client = Client::with(['conversations.user', 'conversations.broker', 'contracts.broker'])->findOrFail($id);
        return view('clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:constant,new',
            'email' => 'nullable|email:rfc,dns|max:255',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\+[0-9]{11}$/'],
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ], [
            'phone.regex' => 'El teléfono debe comenzar con + seguido de exactamente 11 números (ejemplo: +56912345678)',
            'email.email' => 'El formato del correo electrónico no es válido',
        ]);

        $client = Client::findOrFail($id);
        $client->update($request->all());

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente eliminado exitosamente.');
    }

    public function storeConversation(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'broker_id' => 'nullable|exists:brokers,id',
            'stage' => 'required|in:client_contact,stock_offer,negotiation',
            'notes' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // Max 10MB por archivo
        ]);

        // Manejar archivos adjuntos
        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $client = Client::findOrFail($validated['client_id']);
                $path = $file->store('conversations/' . $client->id, 'public');
                $attachmentPaths[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ];
            }
        }

        Conversation::create([
            'client_id' => $request->client_id,
            'broker_id' => $request->broker_id,
            'user_id' => Auth::id(),
            'stage' => $request->stage,
            'notes' => $request->notes,
            'attachments' => $attachmentPaths,
        ]);

        return redirect()->route('clients.index')->with('success', 'Conversación registrada exitosamente.');
    }
}
