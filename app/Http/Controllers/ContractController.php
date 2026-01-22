<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractModification;
use App\Models\ContractDocument;
use App\Models\Client;
use App\Models\Broker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with(['client', 'broker', 'modifications.user'])
            ->latest()
            ->paginate(15);
        
        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        $clients = Client::all();
        $brokers = Broker::all();
        return view('contracts.create', compact('clients', 'brokers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'broker_id' => 'nullable|exists:brokers,id',
            'stock_committed' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'broker_commission_percentage' => 'nullable|numeric|min:1.5|max:3.0',
            'destination_bank' => 'nullable|string|max:255',
            'destination_port' => 'nullable|string|max:255',
            'contract_variations' => 'nullable|string',
            'status' => 'required|in:draft,active,completed,cancelled',
            'contract_number' => 'nullable|string|max:255',
            'contract_date' => 'nullable|date',
            'product_type' => 'nullable|string|max:255',
            'booking_number' => 'nullable|string|max:255',
            'vessel_name' => 'nullable|string|max:255',
            'etd_date' => 'nullable|date',
            'etd_week' => 'nullable|integer|min:1|max:52',
            'eta_date' => 'nullable|date|after_or_equal:etd_date',
            'eta_week' => 'nullable|integer|min:1|max:52',
            'container_number' => 'nullable|string|max:255',
            'transit_weeks' => 'nullable|integer|min:0',
            'freight_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:pending,partial,paid',
            'consignee_name' => 'nullable|string|max:255',
            'consignee_address' => 'nullable|string',
            'consignee_chinese_address' => 'nullable|string',
            'consignee_tax_id' => 'nullable|string|max:255',
            'consignee_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'notify_name' => 'nullable|string|max:255',
            'notify_address' => 'nullable|string',
            'notify_chinese_address' => 'nullable|string',
            'notify_tax_id' => 'nullable|string|max:255',
            'notify_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'contact_person_1_name' => 'nullable|string|max:255',
            'contact_person_1_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'contact_person_2_name' => 'nullable|string|max:255',
            'contact_person_2_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'contact_email' => 'nullable|email:rfc,dns',
            'seller_name' => 'nullable|string|max:255',
            'seller_address' => 'nullable|string',
            'seller_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'product_description' => 'nullable|string',
            'quality_specification' => 'nullable|string',
            'crop_year' => 'nullable|string|max:255',
            'packing' => 'nullable|string|max:255',
            'label_info' => 'nullable|string|max:255',
            'incoterm' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string',
            'required_documents' => 'nullable|string',
            'customer_reference' => 'nullable|string|max:255',
            'port_of_charge' => 'nullable|string|max:255',
            'maturity_date' => 'nullable|date',
            'transportation_details' => 'nullable|string',
            'shipment_schedule' => 'nullable|string',
            'seller_tax_id' => 'nullable|string|max:255',
            'seller_bank_name' => 'nullable|string|max:255',
            'seller_bank_account_number' => 'nullable|string|max:255',
            'seller_bank_swift' => 'nullable|string|max:255',
            'seller_bank_address' => 'nullable|string',
            'payment_type' => 'nullable|string|max:255',
            'contract_clause' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
            'unit_price_per_kg' => 'nullable|numeric|min:0',
        ], [
            'eta_date.after_or_equal' => 'La fecha de llegada (ETA) no puede ser anterior a la fecha de salida (ETD)',
            'consignee_phone.regex' => 'El teléfono del consignatario debe comenzar con + seguido de 11 dígitos',
            'notify_phone.regex' => 'El teléfono de notificación debe comenzar con + seguido de 11 dígitos',
            'contact_person_1_phone.regex' => 'El teléfono del contacto 1 debe comenzar con + seguido de 11 dígitos',
            'contact_person_2_phone.regex' => 'El teléfono del contacto 2 debe comenzar con + seguido de 11 dígitos',
            'seller_phone.regex' => 'El teléfono del vendedor debe comenzar con + seguido de 11 dígitos',
        ]);

        // Validación automática de cifras
        $this->validateContractFigures($validated);

        $contract = Contract::create($validated);

        // Registrar creación en historial
        $this->logModification($contract, 'created', null, 'Contrato creado');

        return redirect()->route('contracts.index')->with('success', 'Contrato creado exitosamente.');
    }

    public function show($id)
    {
        $contract = Contract::with([
            'client',
            'broker',
            'modifications.user',
            'brokerPayments',
            'documents'
        ])->findOrFail($id);
        
        return view('contracts.show', compact('contract'));
    }

    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        $clients = Client::all();
        $brokers = Broker::all();
        
        return view('contracts.edit', compact('contract', 'clients', 'brokers'));
    }

    public function update(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'broker_id' => 'nullable|exists:brokers,id',
            'stock_committed' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'broker_commission_percentage' => 'nullable|numeric|min:1.5|max:3.0',
            'destination_bank' => 'nullable|string|max:255',
            'destination_port' => 'nullable|string|max:255',
            'contract_variations' => 'nullable|string',
            'status' => 'required|in:draft,active,completed,cancelled',
            'contract_number' => 'nullable|string|max:255',
            'contract_date' => 'nullable|date',
            'product_type' => 'nullable|string|max:255',
            'booking_number' => 'nullable|string|max:255',
            'vessel_name' => 'nullable|string|max:255',
            'etd_date' => 'nullable|date',
            'etd_week' => 'nullable|integer|min:1|max:52',
            'eta_date' => 'nullable|date|after_or_equal:etd_date',
            'eta_week' => 'nullable|integer|min:1|max:52',
            'container_number' => 'nullable|string|max:255',
            'transit_weeks' => 'nullable|integer|min:0',
            'freight_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:pending,partial,paid',
            'consignee_name' => 'nullable|string|max:255',
            'consignee_address' => 'nullable|string',
            'consignee_chinese_address' => 'nullable|string',
            'consignee_tax_id' => 'nullable|string|max:255',
            'consignee_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'notify_name' => 'nullable|string|max:255',
            'notify_address' => 'nullable|string',
            'notify_chinese_address' => 'nullable|string',
            'notify_tax_id' => 'nullable|string|max:255',
            'notify_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'contact_person_1_name' => 'nullable|string|max:255',
            'contact_person_1_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'contact_person_2_name' => 'nullable|string|max:255',
            'contact_person_2_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'contact_email' => 'nullable|email:rfc,dns',
            'seller_name' => 'nullable|string|max:255',
            'seller_address' => 'nullable|string',
            'seller_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'product_description' => 'nullable|string',
            'quality_specification' => 'nullable|string',
            'crop_year' => 'nullable|string|max:255',
            'packing' => 'nullable|string|max:255',
            'label_info' => 'nullable|string|max:255',
            'incoterm' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string',
            'required_documents' => 'nullable|string',
            'customer_reference' => 'nullable|string|max:255',
            'port_of_charge' => 'nullable|string|max:255',
            'maturity_date' => 'nullable|date',
            'transportation_details' => 'nullable|string',
            'shipment_schedule' => 'nullable|string',
            'seller_tax_id' => 'nullable|string|max:255',
            'seller_bank_name' => 'nullable|string|max:255',
            'seller_bank_account_number' => 'nullable|string|max:255',
            'seller_bank_swift' => 'nullable|string|max:255',
            'seller_bank_address' => 'nullable|string',
            'payment_type' => 'nullable|string|max:255',
            'contract_clause' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
            'unit_price_per_kg' => 'nullable|numeric|min:0',
        ], [
            'eta_date.after_or_equal' => 'La fecha de llegada (ETA) no puede ser anterior a la fecha de salida (ETD)',
            'consignee_phone.regex' => 'El teléfono del consignatario debe comenzar con + seguido de 11 dígitos',
            'notify_phone.regex' => 'El teléfono de notificación debe comenzar con + seguido de 11 dígitos',
            'contact_person_1_phone.regex' => 'El teléfono del contacto 1 debe comenzar con + seguido de 11 dígitos',
            'contact_person_2_phone.regex' => 'El teléfono del contacto 2 debe comenzar con + seguido de 11 dígitos',
            'seller_phone.regex' => 'El teléfono del vendedor debe comenzar con + seguido de 11 dígitos',
        ]);

        // Validación automática de cifras
        $this->validateContractFigures($validated);

        // Registrar cambios en historial
        $this->logContractChanges($contract, $validated);

        $contract->update($validated);

        return redirect()->route('contracts.index')->with('success', 'Contrato actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();

        return redirect()->route('contracts.index')->with('success', 'Contrato eliminado exitosamente.');
    }

    /**
     * Validación automática de cifras del sistema
     */
    private function validateContractFigures($data)
    {
        $totalValue = $data['stock_committed'] * $data['price'];
        
        if ($data['broker_commission_percentage']) {
            $commission = ($totalValue * $data['broker_commission_percentage']) / 100;
            
            if ($commission > $totalValue) {
                throw new \Exception('La comisión del broker no puede ser mayor al valor total del contrato.');
            }
        }
    }

    /**
     * Registrar cambios en el historial de modificaciones
     */
    private function logContractChanges($contract, $newData)
    {
        $fields = [
            'client_id' => 'Cliente',
            'broker_id' => 'Broker',
            'stock_committed' => 'Stock Comprometido',
            'price' => 'Precio',
            'broker_commission_percentage' => 'Porcentaje de Comisión',
            'destination_bank' => 'Banco de Destino',
            'destination_port' => 'Puerto de Destino',
            'contract_variations' => 'Variaciones del Contrato',
            'status' => 'Estado',
        ];

        foreach ($fields as $field => $label) {
            if (isset($newData[$field]) && $contract->$field != $newData[$field]) {
                ContractModification::create([
                    'contract_id' => $contract->id,
                    'user_id' => Auth::id(),
                    'field_changed' => $label,
                    'old_value' => $contract->$field,
                    'new_value' => $newData[$field],
                    'notes' => "Campo '{$label}' modificado",
                ]);
            }
        }
    }

    /**
     * Registrar una modificación específica
     */
    private function logModification($contract, $field, $oldValue, $notes = null)
    {
        ContractModification::create([
            'contract_id' => $contract->id,
            'user_id' => Auth::id(),
            'field_changed' => $field,
            'old_value' => $oldValue,
            'new_value' => $field === 'created' ? 'Nuevo contrato' : null,
            'notes' => $notes,
        ]);
    }

    // ***** MÉTODOS PARA GENERAR DOCUMENTOS PDF DIRECTAMENTE DESDE EL CONTRATO *****
    
    public function generateBillOfLading($id)
    {
        $contract = Contract::with(['client'])->findOrFail($id);
        $exportation = (object)['export_number' => $contract->contract_number];
        
        $pdf = Pdf::loadView('exportations.documents.bill_of_lading', compact('exportation', 'contract'));
        
        // Guardar en carpeta del contrato
        $fileName = 'Bill_of_Lading_' . $contract->contract_number . '.pdf';
        $filePath = 'contracts/' . $contract->contract_number . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        // Registrar documento
        ContractDocument::updateOrCreate([
            'contract_id' => $contract->id,
            'document_type' => 'bill_of_lading',
        ],[
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    public function generateInvoice($id)
    {
        $contract = Contract::with(['client'])->findOrFail($id);
        $exportation = (object)['export_number' => $contract->contract_number];
        
        $pdf = Pdf::loadView('exportations.documents.invoice', compact('exportation', 'contract'));
        
        $fileName = 'Commercial_Invoice_' . $contract->contract_number . '.pdf';
        $filePath = 'contracts/' . $contract->contract_number . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        ContractDocument::updateOrCreate([
            'contract_id' => $contract->id,
            'document_type' => 'invoice',
        ],[
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    public function generatePackingList($id)
    {
        $contract = Contract::with(['client'])->findOrFail($id);
        $exportation = (object)['export_number' => $contract->contract_number];
        
        $pdf = Pdf::loadView('exportations.documents.packing_list', compact('exportation', 'contract'));
        
        $fileName = 'Packing_List_' . $contract->contract_number . '.pdf';
        $filePath = 'contracts/' . $contract->contract_number . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        ContractDocument::updateOrCreate([
            'contract_id' => $contract->id,
            'document_type' => 'packing_list',
        ],[
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    public function generateCertificateOfOrigin($id)
    {
        $contract = Contract::with(['client'])->findOrFail($id);
        $exportation = (object)['export_number' => $contract->contract_number];
        
        $pdf = Pdf::loadView('exportations.documents.certificate_of_origin', compact('exportation', 'contract'));
        
        $fileName = 'Certificate_of_Origin_' . $contract->contract_number . '.pdf';
        $filePath = 'contracts/' . $contract->contract_number . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        ContractDocument::updateOrCreate([
            'contract_id' => $contract->id,
            'document_type' => 'certificate_origin',
        ],[
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    public function generatePhytosanitaryCertificate($id)
    {
        $contract = Contract::with(['client'])->findOrFail($id);
        $exportation = (object)['export_number' => $contract->contract_number];
        
        $pdf = Pdf::loadView('exportations.documents.phytosanitary', compact('exportation', 'contract'));
        
        $fileName = 'Phytosanitary_Certificate_' . $contract->contract_number . '.pdf';
        $filePath = 'contracts/' . $contract->contract_number . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        ContractDocument::updateOrCreate([
            'contract_id' => $contract->id,
            'document_type' => 'phytosanitary',
        ],[
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);
        
        return $pdf->download($fileName);
    }

    public function generateQualityCertificate($id)
    {
        $contract = Contract::with(['client'])->findOrFail($id);
        
        $pdf = Pdf::loadView('contracts.documents.quality_certificate', compact('contract'));
        
        $fileName = 'Quality_Certificate_' . $contract->contract_number . '.pdf';
        $filePath = 'contracts/' . $contract->contract_number . '/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        
        ContractDocument::updateOrCreate([
            'contract_id' => $contract->id,
            'document_type' => 'calidad',
        ],[
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

        $contract = Contract::findOrFail($id);
        $document = ContractDocument::findOrFail($validated['document_id']);
        $exportation = (object)[
            'export_number' => $contract->contract_number,
            'contract' => $contract
        ];

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
}
