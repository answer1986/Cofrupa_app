<?php

namespace App\Http\Controllers;

use App\Models\Exportation;
use App\Models\Shipment;
use App\Models\Contract;
use App\Models\ContractDocument;
use App\Models\ExportationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportationController extends Controller
{
    public function index()
    {
        $exportations = Exportation::with([
            'shipment.contract.client',
            'contract.client',
            'documents'
        ])->latest()->paginate(15);
        
        return view('exportations.index', compact('exportations'));
    }

    public function create()
    {
        // Traer despachos que no estén cancelados y que tengan contrato asociado
        $shipments = Shipment::with(['contract.client', 'contract.broker'])
            ->where('status', '!=', 'cancelled')
            ->whereHas('contract', function($query) {
                $query->whereIn('status', ['draft', 'active', 'completed']);
            })
            ->orderBy('scheduled_date', 'desc')
            ->get();
        
        return view('exportations.create', compact('shipments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'export_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Obtener el contrato del despacho automáticamente
        $shipment = Shipment::findOrFail($validated['shipment_id']);
        $contract = $shipment->contract;
        $validated['contract_id'] = $contract->id;

        // Generar número de exportación basado en el contrato
        $validated['export_number'] = $contract->contract_number ?? ('EXP-' . str_pad(Exportation::max('id') + 1, 6, '0', STR_PAD_LEFT));
        $validated['status'] = 'preparation';
        $validated['folder_path'] = 'exportations/' . $validated['export_number'];

        $exportation = Exportation::create($validated);

        // Crear carpeta digital
        Storage::disk('public')->makeDirectory($validated['folder_path']);

        return redirect()->route('exportations.show', $exportation->id)
            ->with('success', 'Exportación creada exitosamente. Carpeta: ' . $validated['export_number']);
    }

    public function show($id)
    {
        $exportation = Exportation::with([
            'shipment.contract.client.broker',
            'contract.client',
            'contract.documents',
            'documents'
        ])->findOrFail($id);
        
        return view('exportations.show', compact('exportation'));
    }

    // Generar Bill of Lading PDF
    public function generateBillOfLading($id)
    {
        $exportation = Exportation::with(['shipment.contract.client', 'contract'])->findOrFail($id);
        $contract = $exportation->contract;
        
        $pdf = Pdf::loadView('exportations.documents.bill_of_lading', compact('exportation', 'contract'));
        
        // Guardar en la carpeta de la exportación
        $fileName = 'Bill_of_Lading_' . $exportation->export_number . '.pdf';
        $filePath = $exportation->folder_path . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        // Registrar documento
        ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => 'bill_of_lading',
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    // Generar Invoice PDF
    public function generateInvoice($id)
    {
        $exportation = Exportation::with(['shipment.contract.client', 'contract'])->findOrFail($id);
        $contract = $exportation->contract;
        
        $pdf = Pdf::loadView('exportations.documents.invoice', compact('exportation', 'contract'));
        
        $fileName = 'Commercial_Invoice_' . $exportation->export_number . '.pdf';
        $filePath = $exportation->folder_path . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => 'invoice',
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    // Generar Packing List PDF
    public function generatePackingList($id)
    {
        $exportation = Exportation::with(['shipment.contract.client', 'contract'])->findOrFail($id);
        $contract = $exportation->contract;
        
        $pdf = Pdf::loadView('exportations.documents.packing_list', compact('exportation', 'contract'));
        
        $fileName = 'Packing_List_' . $exportation->export_number . '.pdf';
        $filePath = $exportation->folder_path . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => 'packing_list',
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    // Generar Certificado de Origen PDF
    public function generateCertificateOfOrigin($id)
    {
        $exportation = Exportation::with(['shipment.contract.client', 'contract'])->findOrFail($id);
        $contract = $exportation->contract;
        
        $pdf = Pdf::loadView('exportations.documents.certificate_of_origin', compact('exportation', 'contract'));
        
        $fileName = 'Certificate_of_Origin_' . $exportation->export_number . '.pdf';
        $filePath = $exportation->folder_path . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => 'certificate_origin',
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    // Generar Certificado Fitosanitario PDF
    public function generatePhytosanitaryCertificate($id)
    {
        $exportation = Exportation::with(['shipment.contract.client', 'contract'])->findOrFail($id);
        $contract = $exportation->contract;
        
        $pdf = Pdf::loadView('exportations.documents.phytosanitary', compact('exportation', 'contract'));
        
        $fileName = 'Phytosanitary_Certificate_' . $exportation->export_number . '.pdf';
        $filePath = $exportation->folder_path . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => 'phytosanitary',
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    // Generar Certificado de Calidad PDF
    public function generateQualityCertificate($id)
    {
        $exportation = Exportation::with(['shipment.contract.client', 'contract'])->findOrFail($id);
        $contract = $exportation->contract;
        
        $pdf = Pdf::loadView('contracts.documents.quality_certificate', compact('contract'));
        
        $fileName = 'Quality_Certificate_' . $exportation->export_number . '.pdf';
        $filePath = $exportation->folder_path . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => 'calidad',
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    // Enviar documento por email
    public function sendDocument(Request $request, $id)
    {
        $validated = $request->validate([
            'document_id' => 'required|exists:contract_documents,id',
            'recipient_email' => 'required|email',
            'recipient_name' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        $exportation = Exportation::findOrFail($id);
        $document = ContractDocument::findOrFail($validated['document_id']);

        // Enviar email con el documento adjunto
        Mail::send('emails.document_send', [
            'exportation' => $exportation,
            'document' => $document,
            'recipient_name' => $validated['recipient_name'],
            'message' => $validated['message'],
        ], function($mail) use ($document, $validated) {
            $mail->to($validated['recipient_email'])
                 ->subject('Documento: ' . $document->document_name)
                 ->attach(Storage::disk('public')->path($document->file_path));
        });

        return back()->with('success', 'Documento enviado exitosamente a ' . $validated['recipient_email']);
    }

    public function uploadDocument(Request $request, $id)
    {
        $exportation = Exportation::findOrFail($id);
        
        $validated = $request->validate([
            'document_type' => 'required|string',
            'file' => 'required|file|max:20480', // 20MB max
            'notes' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $filePath = $exportation->folder_path . '/' . $fileName;
        
        Storage::disk('public')->put($filePath, file_get_contents($file));

        ContractDocument::create([
            'contract_id' => $exportation->contract_id,
            'document_type' => $validated['document_type'],
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'notes' => $validated['notes'] ?? null,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('exportations.show', $exportation->id)
            ->with('success', 'Documento subido exitosamente.');
    }

    public function edit($id)
    {
        $exportation = Exportation::findOrFail($id);
        $shipments = Shipment::with(['contract.client', 'contract.broker'])
            ->where('status', '!=', 'cancelled')
            ->whereHas('contract', function($query) {
                $query->whereIn('status', ['draft', 'active', 'completed']);
            })
            ->orderBy('scheduled_date', 'desc')
            ->get();
        
        return view('exportations.edit', compact('exportation', 'shipments'));
    }

    public function update(Request $request, $id)
    {
        $exportation = Exportation::findOrFail($id);
        
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'status' => 'required|in:preparation,in_progress,completed,cancelled',
            'export_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Obtener el contrato del despacho automáticamente
        $shipment = Shipment::findOrFail($validated['shipment_id']);
        $validated['contract_id'] = $shipment->contract_id;

        $exportation->update($validated);

        return redirect()->route('exportations.index')->with('success', 'Exportación actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $exportation = Exportation::findOrFail($id);
        
        // Eliminar carpeta y archivos
        if ($exportation->folder_path && Storage::disk('public')->exists($exportation->folder_path)) {
            Storage::disk('public')->deleteDirectory($exportation->folder_path);
        }
        
        $exportation->delete();

        return redirect()->route('exportations.index')->with('success', 'Exportación eliminada exitosamente.');
    }
}
