<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('shipment.contract.client')
            ->latest()
            ->paginate(15);
        
        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $shipments = Shipment::with('contract.client')->where('status', '!=', 'cancelled')->get();
        return view('documents.create', compact('shipments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'document_type' => 'required|in:export_guide_plant,export_guide_transport,customs_loading,dvl_matrix,master_document',
            'recipient' => 'required|in:plant,customs,transport,embarkation',
            'recipient_company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['document_number'] = 'DOC-' . strtoupper(Str::random(8));
        $validated['status'] = 'draft';

        Document::create($validated);

        return redirect()->route('documents.index')->with('success', 'Documento creado exitosamente.');
    }

    public function show($id)
    {
        $document = Document::with('shipment.contract.client')->findOrFail($id);
        return view('documents.show', compact('document'));
    }

    public function generate($id)
    {
        $document = Document::with('shipment.contract.client')->findOrFail($id);
        
        // Aquí se implementaría la lógica de generación del documento
        // Por ahora solo actualizamos el estado
        
        $document->update([
            'status' => 'generated',
            'generated_at' => now(),
        ]);

        return redirect()->route('documents.show', $document->id)
            ->with('success', 'Documento generado exitosamente.');
    }

    public function send($id)
    {
        $document = Document::with('shipment.contract.client')->findOrFail($id);
        
        // Aquí se implementaría la lógica de envío del documento
        // Por ahora solo actualizamos el estado
        
        $document->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return redirect()->route('documents.show', $document->id)
            ->with('success', 'Documento enviado exitosamente.');
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $shipments = Shipment::with('contract.client')->where('status', '!=', 'cancelled')->get();
        
        return view('documents.edit', compact('document', 'shipments'));
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'document_type' => 'required|in:export_guide_plant,export_guide_transport,customs_loading,dvl_matrix,master_document',
            'recipient' => 'required|in:plant,customs,transport,embarkation',
            'recipient_company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $document->update($validated);

        return redirect()->route('documents.index')->with('success', 'Documento actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Documento eliminado exitosamente.');
    }
}
