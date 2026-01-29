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
        $customsAgencies = \App\Models\CustomsAgency::paginate(15);
        
        return view('clients.index', compact('clients', 'brokers', 'conversations', 'shippingLines', 'logisticsCompanies', 'customsAgencies'));
    }

    public function create()
    {
        $incompleteClients = Client::where('is_incomplete', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $incompleteClientData = [];
        $incompleteClient = $incompleteClients->first();
        if ($incompleteClient) {
            $incompleteClientData = [
                'name' => $incompleteClient->name,
                'client_id' => $incompleteClient->id,
            ];
        }

        return view('clients.create', compact('incompleteClients', 'incompleteClientData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:constant,new',
            'email' => 'nullable|email:rfc,dns|max:255',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\+[0-9]{11}$/'],
            'customs_agency' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'incomplete_client_id' => 'nullable|exists:clients,id',
        ], [
            'phone.regex' => 'El teléfono debe comenzar con + seguido de exactamente 11 números (ejemplo: +56912345678)',
            'email.email' => 'El formato del correo electrónico no es válido',
        ]);

        $data = $request->except('incomplete_client_id');

        if ($request->filled('incomplete_client_id')) {
            $incompleteClient = Client::find($request->incomplete_client_id);
            if ($incompleteClient && $incompleteClient->is_incomplete) {
                $data['is_incomplete'] = false;
                $incompleteClient->update($data);
                return redirect()->route('clients.index')->with('success', 'Cliente completado exitosamente.');
            }
        }

        $data['is_incomplete'] = false;
        Client::create($data);

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
            'customs_agency' => 'nullable|string|max:255',
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
