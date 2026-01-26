<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentGeneratorController extends Controller
{
    /**
     * Actualizar campos del contrato desde los datos del formulario
     */
    private function updateContractFields(Contract $contract, Request $request)
    {
        $contractUpdates = [];
        
        if ($request->has('contract_product_description')) {
            $contractUpdates['product_description'] = $request->contract_product_description;
        }
        if ($request->has('contract_packing')) {
            $contractUpdates['packing'] = $request->contract_packing;
        }
        if ($request->has('contract_crop_year')) {
            $contractUpdates['crop_year'] = $request->contract_crop_year;
        }
        if ($request->has('contract_quality_specification')) {
            $contractUpdates['quality_specification'] = $request->contract_quality_specification;
        }
        if ($request->has('contract_humidity')) {
            $contractUpdates['humidity'] = $request->contract_humidity;
        }
        if ($request->has('contract_total_defects')) {
            $contractUpdates['total_defects'] = $request->contract_total_defects;
        }
        
        if (!empty($contractUpdates)) {
            $contract->update($contractUpdates);
            return true;
        }
        
        return false;
    }
    /**
     * Listar contratos disponibles para Certificado de Calidad
     */
    public function listQualityCertificates()
    {
        $contracts = Contract::with('client')
            ->whereIn('status', ['active', 'completed'])
            ->latest()
            ->get();
        
        return view('documents.quality_certificate_list', compact('contracts'));
    }

    /**
     * Mostrar formulario para editar/crear Certificado de Calidad
     */
    public function editQualityCertificate($contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        // Datos por defecto del certificado
        $certificate = [
            'exporter' => 'COFRUPA / Registration CSE 153105',
            'production_plant' => 'Agrícola Siemel / Registration Nr. CCHL1301211201016',
            'container_nr' => $contract->container_number,
            'bl_nr' => $contract->booking_number,
            'invoice_nr' => $contract->contract_number,
            'product' => $contract->product_description ?? "Chilean D'Agen Prunes Natural condition",
            'size' => $contract->packing ?? '120/144 LOT COF 81',
            'production_date' => now()->format('Y-m-d'),
            'expiration_date' => now()->addYear()->format('Y-m-d'),
            'size_allowance' => '120/144',
            'size_result' => '129',
            'moisture_result' => '18',
            'defects_result' => '4.4',
        ];
        
        return view('documents.quality_certificate_edit', compact('contract', 'certificate'));
    }

    /**
     * Guardar Certificado de Calidad
     */
    public function storeQualityCertificate(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $validated = $request->validate([
            'exporter' => 'required|string',
            'production_plant' => 'required|string',
            'container_nr' => 'nullable|string',
            'bl_nr' => 'nullable|string',
            'invoice_nr' => 'required|string',
            'product' => 'required|string',
            'size' => 'required|string',
            'production_date' => 'required|date',
            'expiration_date' => 'required|date',
            'size_allowance' => 'nullable|string',
            'size_result' => 'nullable|string',
            'moisture_result' => 'nullable|string',
            'defects_result' => 'nullable|string',
            // Campos del contrato
            'contract_product_description' => 'nullable|string',
            'contract_packing' => 'nullable|string|max:255',
            'contract_crop_year' => 'nullable|string|max:255',
            'contract_quality_specification' => 'nullable|string',
            'contract_humidity' => 'nullable|string|max:255',
            'contract_total_defects' => 'nullable|string|max:255',
        ]);

        // Actualizar campos del contrato si se proporcionaron
        $this->updateContractFields($contract, $request);

        // Generar PDF con los datos editados
        $pdf = Pdf::loadView('documents.pdfs.quality_certificate', [
            'contract' => $contract->fresh(), // Recargar para tener los datos actualizados
            'certificate' => $validated
        ]);

        // Guardar en carpeta del contrato
        $fileName = 'Quality_Certificate_' . $contract->contract_number . '_' . now()->format('YmdHis') . '.pdf';
        $folderPath = 'contracts/' . $contract->contract_number;
        $filePath = $folderPath . '/' . $fileName;
        
        Storage::disk('public')->put($filePath, $pdf->output());

        // Registrar en base de datos
        ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => 'calidad',
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('exportations.index')
            ->with('success', 'Certificado de Calidad generado y guardado. El contrato ha sido actualizado con los nuevos datos.');
    }

    /**
     * Preview del Certificado de Calidad (sin guardar)
     */
    public function previewQualityCertificate(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        // Usar los datos del request o valores por defecto
        $certificate = [
            'exporter' => $request->input('exporter', 'COFRUPA / Registration CSE 153105'),
            'production_plant' => $request->input('production_plant', 'Agrícola Siemel / Registration Nr. CCHL1301211201016'),
            'container_nr' => $request->input('container_nr', $contract->container_number),
            'bl_nr' => $request->input('bl_nr', $contract->booking_number),
            'invoice_nr' => $request->input('invoice_nr', $contract->contract_number),
            'product' => $request->input('product', $contract->product_description),
            'size' => $request->input('size', $contract->packing),
            'production_date' => $request->input('production_date', now()->format('Y-m-d')),
            'expiration_date' => $request->input('expiration_date', now()->addYear()->format('Y-m-d')),
            'size_allowance' => $request->input('size_allowance', '120/144'),
            'size_result' => $request->input('size_result', '129'),
            'moisture_result' => $request->input('moisture_result', '18'),
            'defects_result' => $request->input('defects_result', '4.4'),
        ];

        // Generar PDF y mostrarlo directamente en el navegador
        $pdf = Pdf::loadView('documents.pdfs.quality_certificate', [
            'contract' => $contract,
            'certificate' => $certificate
        ]);

        return $pdf->stream('Preview_Quality_Certificate_' . $contract->contract_number . '.pdf');
    }

    /**
     * Enviar Certificado de Calidad por email
     */
    public function sendQualityCertificate(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $validated = $request->validate([
            'emails' => 'required|string',
            'message' => 'nullable|string',
            'exporter' => 'required|string',
            'production_plant' => 'required|string',
            'container_nr' => 'nullable|string',
            'bl_nr' => 'nullable|string',
            'invoice_nr' => 'required|string',
            'product' => 'required|string',
            'size' => 'required|string',
            'production_date' => 'required|date',
            'expiration_date' => 'required|date',
            'size_allowance' => 'nullable|string',
            'size_result' => 'nullable|string',
            'moisture_result' => 'nullable|string',
            'defects_result' => 'nullable|string',
        ]);

        // Separar emails por coma o punto y coma
        $emails = preg_split('/[,;]+/', $validated['emails']);
        $emails = array_map('trim', $emails);
        $emails = array_filter($emails, function($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });

        if (empty($emails)) {
            return back()->with('error', 'No se proporcionaron emails válidos');
        }

        // Generar PDF
        $certificate = [
            'exporter' => $validated['exporter'],
            'production_plant' => $validated['production_plant'],
            'container_nr' => $validated['container_nr'],
            'bl_nr' => $validated['bl_nr'],
            'invoice_nr' => $validated['invoice_nr'],
            'product' => $validated['product'],
            'size' => $validated['size'],
            'production_date' => $validated['production_date'],
            'expiration_date' => $validated['expiration_date'],
            'size_allowance' => $validated['size_allowance'],
            'size_result' => $validated['size_result'],
            'moisture_result' => $validated['moisture_result'],
            'defects_result' => $validated['defects_result'],
        ];

        $pdf = Pdf::loadView('documents.pdfs.quality_certificate', [
            'contract' => $contract,
            'certificate' => $certificate
        ]);

        $fileName = 'Quality_Certificate_' . $contract->contract_number . '.pdf';

        // Enviar a cada destinatario
        foreach ($emails as $email) {
            \Mail::send('emails.document_certificate', [
                'contract' => $contract,
                'message' => $validated['message'] ?? 'Adjunto encontrará el Certificado de Calidad correspondiente al contrato ' . $contract->contract_number,
            ], function($mail) use ($email, $fileName, $pdf, $contract) {
                $mail->to($email)
                    ->subject('Certificado de Calidad - Contrato ' . $contract->contract_number)
                    ->attachData($pdf->output(), $fileName, [
                        'mime' => 'application/pdf',
                    ]);
            });
        }

        return back()->with('success', 'Certificado enviado exitosamente a ' . count($emails) . ' destinatario(s)');
    }

    // ==================== CERTIFICADO DE CALIDAD EU ====================
    
    /**
     * Listar contratos disponibles para Certificado de Calidad EU
     */
    public function listQualityCertificatesEU()
    {
        $contracts = Contract::with('client')
            ->whereIn('status', ['active', 'completed'])
            ->latest()
            ->get();
        
        return view('documents.quality_certificate_eu_list', compact('contracts'));
    }

    /**
     * Mostrar formulario para editar/crear Certificado de Calidad EU
     */
    public function editQualityCertificateEU($contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        // Datos por defecto del certificado EU
        $certificate = [
            'emission_date' => now()->format('Y-m-d'),
            'client_name' => $contract->client->name ?? 'N/A',
            'product' => $contract->product_description ?? 'PITTED PRUNES',
            'size' => $contract->packing ?? 'EX 70/80',
            'quantity' => $contract->stock_committed . ' KG' ?? 'N/A',
            'contract_number' => $contract->contract_number,
            'invoice_nr' => $contract->contract_number,
            'vessel' => $contract->vessel_name ?? 'N/A',
            'bl_nr' => $contract->booking_number ?? 'N/A',
            'fcl' => $contract->container_number ?? 'N/A',
            'origin' => 'VALPARAISO, CHILE',
            'destination' => $contract->destination_port ?? 'N/A',
            // Organoleptic Analysis
            'colour' => 'Uniform, Typical',
            'flavour' => 'Characteristic',
            'texture' => 'Good',
            // Chemical Analysis
            'moisture' => '30.5%',
            'moisture_method' => 'DFA OF CALIFORNIA MOISTURE TESTER',
            'potassium_sorbate' => '800 PPM',
            'oil' => 'N/A',
            // Physical Analysis
            'fragments_pits' => '0,26%',
            'units_per_pound' => '91 UNITS',
            'defects' => '3.6%',
            'usda_grade' => 'A',
            'usda_reference' => 'REF: USDA NORMA PROCESSED PRUNES',
            // Microbiology
            'total_plate_count' => '<100 CFU/g (Plate count estimate)',
            'moulds' => '<10 CFU/g',
            'yeasts' => '<10 CFU/g',
            'e_coli' => '<10 CFU/g',
            'salmonella' => 'Absence/25g',
            'aflatoxine_individual' => 'Not detected (limit detection 4 ppb; B1 2 ppb)',
            'aflatoxine_total' => 'Not detected',
            // Dates
            'production_date' => 'OCTOBER 2025',
            'expiry_date' => 'OCTOBER 2026',
        ];
        
        return view('documents.quality_certificate_eu_edit', compact('contract', 'certificate'));
    }

    /**
     * Guardar Certificado de Calidad EU
     */
    public function storeQualityCertificateEU(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $validated = $request->validate([
            'emission_date' => 'required|date',
            'client_name' => 'required|string',
            'product' => 'required|string',
            'size' => 'required|string',
            'quantity' => 'required|string',
            'contract_number' => 'required|string',
            'invoice_nr' => 'required|string',
            'vessel' => 'nullable|string',
            'bl_nr' => 'nullable|string',
            'fcl' => 'nullable|string',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'colour' => 'required|string',
            'flavour' => 'required|string',
            'texture' => 'required|string',
            'moisture' => 'required|string',
            'moisture_method' => 'nullable|string',
            'potassium_sorbate' => 'required|string',
            'oil' => 'nullable|string',
            'fragments_pits' => 'required|string',
            'units_per_pound' => 'required|string',
            'defects' => 'required|string',
            'usda_grade' => 'required|string',
            'usda_reference' => 'nullable|string',
            'total_plate_count' => 'required|string',
            'moulds' => 'required|string',
            'yeasts' => 'required|string',
            'e_coli' => 'required|string',
            'salmonella' => 'required|string',
            'aflatoxine_individual' => 'required|string',
            'aflatoxine_total' => 'required|string',
            'production_date' => 'required|string',
            'expiry_date' => 'required|string',
            // Campos del contrato
            'contract_product_description' => 'nullable|string',
            'contract_packing' => 'nullable|string|max:255',
            'contract_crop_year' => 'nullable|string|max:255',
            'contract_quality_specification' => 'nullable|string',
            'contract_humidity' => 'nullable|string|max:255',
            'contract_total_defects' => 'nullable|string|max:255',
        ]);

        // Actualizar campos del contrato si se proporcionaron
        $this->updateContractFields($contract, $request);

        // Generar PDF con los datos editados
        $pdf = Pdf::loadView('documents.pdfs.quality_certificate_eu', [
            'contract' => $contract->fresh(),
            'certificate' => $validated
        ]);

        // Guardar en carpeta del contrato
        $fileName = 'Quality_Certificate_EU_' . $contract->contract_number . '_' . now()->format('YmdHis') . '.pdf';
        $folderPath = 'contracts/' . $contract->contract_number;
        $filePath = $folderPath . '/' . $fileName;
        
        Storage::disk('public')->put($filePath, $pdf->output());

        // Registrar en base de datos
        ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => 'calidad_eu',
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('exportations.index')
            ->with('success', 'Certificado de Calidad EU generado y guardado. El contrato ha sido actualizado con los nuevos datos.');
    }

    /**
     * Preview del Certificado de Calidad EU (sin guardar)
     */
    public function previewQualityCertificateEU(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        // Usar los datos del request
        $certificate = [
            'emission_date' => $request->input('emission_date'),
            'client_name' => $request->input('client_name'),
            'product' => $request->input('product'),
            'size' => $request->input('size'),
            'quantity' => $request->input('quantity'),
            'contract_number' => $request->input('contract_number'),
            'invoice_nr' => $request->input('invoice_nr'),
            'vessel' => $request->input('vessel'),
            'bl_nr' => $request->input('bl_nr'),
            'fcl' => $request->input('fcl'),
            'origin' => $request->input('origin'),
            'destination' => $request->input('destination'),
            'colour' => $request->input('colour'),
            'flavour' => $request->input('flavour'),
            'texture' => $request->input('texture'),
            'moisture' => $request->input('moisture'),
            'moisture_method' => $request->input('moisture_method'),
            'potassium_sorbate' => $request->input('potassium_sorbate'),
            'oil' => $request->input('oil'),
            'fragments_pits' => $request->input('fragments_pits'),
            'units_per_pound' => $request->input('units_per_pound'),
            'defects' => $request->input('defects'),
            'usda_grade' => $request->input('usda_grade'),
            'usda_reference' => $request->input('usda_reference'),
            'total_plate_count' => $request->input('total_plate_count'),
            'moulds' => $request->input('moulds'),
            'yeasts' => $request->input('yeasts'),
            'e_coli' => $request->input('e_coli'),
            'salmonella' => $request->input('salmonella'),
            'aflatoxine_individual' => $request->input('aflatoxine_individual'),
            'aflatoxine_total' => $request->input('aflatoxine_total'),
            'production_date' => $request->input('production_date'),
            'expiry_date' => $request->input('expiry_date'),
        ];

        // Generar PDF y mostrarlo directamente en el navegador
        $pdf = Pdf::loadView('documents.pdfs.quality_certificate_eu', [
            'contract' => $contract,
            'certificate' => $certificate
        ]);

        return $pdf->stream('Preview_Quality_Certificate_EU_' . $contract->contract_number . '.pdf');
    }

    /**
     * Enviar Certificado de Calidad EU por email
     */
    public function sendQualityCertificateEU(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $validated = $request->validate([
            'emails' => 'required|string',
            'message' => 'nullable|string',
        ]);

        // Separar emails
        $emails = preg_split('/[,;]+/', $validated['emails']);
        $emails = array_map('trim', $emails);
        $emails = array_filter($emails, function($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });

        if (empty($emails)) {
            return back()->with('error', 'No se proporcionaron emails válidos');
        }

        // Obtener datos del certificado del request
        $certificate = [
            'emission_date' => $request->input('emission_date'),
            'client_name' => $request->input('client_name'),
            'product' => $request->input('product'),
            'size' => $request->input('size'),
            'quantity' => $request->input('quantity'),
            'contract_number' => $request->input('contract_number'),
            'invoice_nr' => $request->input('invoice_nr'),
            'vessel' => $request->input('vessel'),
            'bl_nr' => $request->input('bl_nr'),
            'fcl' => $request->input('fcl'),
            'origin' => $request->input('origin'),
            'destination' => $request->input('destination'),
            'colour' => $request->input('colour'),
            'flavour' => $request->input('flavour'),
            'texture' => $request->input('texture'),
            'moisture' => $request->input('moisture'),
            'moisture_method' => $request->input('moisture_method'),
            'potassium_sorbate' => $request->input('potassium_sorbate'),
            'oil' => $request->input('oil'),
            'fragments_pits' => $request->input('fragments_pits'),
            'units_per_pound' => $request->input('units_per_pound'),
            'defects' => $request->input('defects'),
            'usda_grade' => $request->input('usda_grade'),
            'usda_reference' => $request->input('usda_reference'),
            'total_plate_count' => $request->input('total_plate_count'),
            'moulds' => $request->input('moulds'),
            'yeasts' => $request->input('yeasts'),
            'e_coli' => $request->input('e_coli'),
            'salmonella' => $request->input('salmonella'),
            'aflatoxine_individual' => $request->input('aflatoxine_individual'),
            'aflatoxine_total' => $request->input('aflatoxine_total'),
            'production_date' => $request->input('production_date'),
            'expiry_date' => $request->input('expiry_date'),
        ];

        $pdf = Pdf::loadView('documents.pdfs.quality_certificate_eu', [
            'contract' => $contract,
            'certificate' => $certificate
        ]);

        $fileName = 'Quality_Certificate_EU_' . $contract->contract_number . '.pdf';

        // Enviar a cada destinatario
        foreach ($emails as $email) {
            \Mail::send('emails.document_certificate', [
                'contract' => $contract,
                'message' => $validated['message'] ?? 'Adjunto encontrará el Certificado de Calidad (EU) correspondiente al contrato ' . $contract->contract_number,
            ], function($mail) use ($email, $fileName, $pdf, $contract) {
                $mail->to($email)
                    ->subject('Certificado de Calidad EU - Contrato ' . $contract->contract_number)
                    ->attachData($pdf->output(), $fileName, [
                        'mime' => 'application/pdf',
                    ]);
            });
        }

        return back()->with('success', 'Certificado EU enviado exitosamente a ' . count($emails) . ' destinatario(s)');
    }

    /**
     * Listar contratos disponibles para Instructivo de Embarque
     */
    public function listShippingInstructions()
    {
        $contracts = Contract::with('client')
            ->whereIn('status', ['active', 'completed'])
            ->latest()
            ->get();
        
        return view('documents.shipping_instructions_list', compact('contracts'));
    }

    /**
     * Mostrar formulario para editar/crear Instructivo de Embarque
     */
    public function editShippingInstructions($contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $shipping = [
            'agent_name' => 'AGENCIA CARLO ROSSI SOFFIA Y CIA LTDA',
            'ref_contract' => 'REF: CONTRACT ' . $contract->contract_number . ' REF SEM47',
            'csnee' => $contract->consignee_name ?? 'NOVA FRUITS INTERNATIONAL GIDA SAN.VE TIC A.S.',
            'contract_number' => $contract->contract_number,
            'numbers_container' => $contract->container_number ?? '1 X 20\' DRY ST',
            'booking' => $contract->booking_number,
            'carrier' => 'MSC',
            'ship' => $contract->vessel_name ?? 'MSC SERENA-NX547R',
            'loading_port' => 'VALPARAISO, CHILE',
            'destination_port' => $contract->destination_port ?? 'IZMIR, TURQUIA',
            'destination_final' => $contract->destination_port ?? 'IZMIR, TURQUIA',
            'clausula_venta' => $contract->incoterm ?? 'CFR',
            'flete' => 'PREPAID',
            'deposito' => 'Medlog - Valparaíso',
            'modalidad_venta' => 'A Firme',
            'forma_pago' => $contract->payment_terms ?? '30 days after B/L date',
            'precio_venta' => 'EX 70-80 3,60 USD/KG',
            'documents' => '(Invoice, Packing list, Certificate of Origin., Quality certificate, PHYTOSANITARY)',
            'hs_code' => 'HS CODE 081320',
            'valor_fob' => '',
            'etd' => $contract->etd_date ? \Carbon\Carbon::parse($contract->etd_date)->format('d/m/Y') . ' (Sem. ' . $contract->etd_week . ')' : '',
            'cut_off' => '',
            'matriz' => $contract->contact_email ?? 'sgonzalez@cofrupa.cl',
            'stacking' => '15-11/19-11',
            'terminal_entrega' => 'Terminal Pacífico Sur',
            'puerto_ingreso' => 'Valparaiso',
            'horario_stacking' => '08:00-15:00',
            'container_type' => '1x20 hc',
            'net_weight' => $contract->stock_committed ?? '21.500',
            'detail' => "PITTED SORTBATED\nPRUNES SIZE EX 80/90\nCROP 2025 .ORIGIN CHILE\nBOXES OF 10 KG, NET CONTRACT " . $contract->contract_number . " REF SEM47 HSCODE 081320",
            'unit_price' => '3,35',
            'total_boxes' => '2150',
            'total_net_weight' => $contract->stock_committed ?? '21.500',
            'total_pallet' => '0',
            'total_gross_weight' => '',
            'net_boxes' => 'NET BOXES 10KG',
            'gross_bags' => 'GROSS BAGS 10,5KG',
            'shipper_info' => "COFRUPA EXPORT SPA\nCAMINO LO MACKENNA PARCELA 7 -A BUIN\nSANTIAGO - CHILE RUT: 76.505.934-8",
            'consignee_info' => ($contract->consignee_name ?? 'NOVA FRUITS INTERNATIONAL GIDA SAN.VE TIC A.S.') . "\n" .
                                 "[ROL SOK. NO:10 EGE SERBEST BÖLGESI\n35410 GAZIEMlR-lZMlR / TURKEY\n35 36\nFAX:+90(232)252 35 36\nEmail: info@novafruits.com.tr",
            'notify_info' => ($contract->consignee_name ?? 'NOVA FRUITS INTERNATIONAL GIDA SAN.VE TIC A.S.') . "\n" .
                             "[ROL SOK. NO:10 EGE SERBEST BÖLGESI\n35410 GAZIEMlR-lZMlR / TURKEY\n35 36\nFAX:+90(232)252 35 36\nEmail: info@novafruits.com.tr",
        ];
        
        return view('documents.shipping_instructions_edit', compact('contract', 'shipping'));
    }

    /**
     * Guardar Instructivo de Embarque
     */
    public function storeShippingInstructions(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $validated = $request->except(['_token']);

        // Actualizar campos del contrato si se proporcionaron
        $this->updateContractFields($contract, $request);

        // Generar PDF
        $pdf = Pdf::loadView('documents.pdfs.shipping_instructions', [
            'contract' => $contract->fresh(),
            'shipping' => $validated
        ]);

        // Guardar
        $fileName = 'Instructivo_Embarque_' . $contract->contract_number . '_' . now()->format('YmdHis') . '.pdf';
        $folderPath = 'contracts/' . $contract->contract_number;
        $filePath = $folderPath . '/' . $fileName;
        
        Storage::disk('public')->put($filePath, $pdf->output());

        ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => 'instructivo_embarque',
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('exportations.index')
            ->with('success', 'Instructivo de Embarque generado y guardado. El contrato ha sido actualizado con los nuevos datos.');
    }

    /**
     * Preview Instructivo de Embarque
     */
    public function previewShippingInstructions(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        $shipping = $request->except(['_token']);

        $pdf = Pdf::loadView('documents.pdfs.shipping_instructions', [
            'contract' => $contract,
            'shipping' => $shipping
        ]);

        return $pdf->stream('Preview_Instructivo_Embarque_' . $contract->contract_number . '.pdf');
    }

    /**
     * Enviar Instructivo de Embarque por email
     */
    public function sendShippingInstructions(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $emails = preg_split('/[,;]+/', $request->input('emails', ''));
        $emails = array_map('trim', $emails);
        $emails = array_filter($emails, fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL));

        if (empty($emails)) {
            return back()->with('error', 'No se proporcionaron emails válidos');
        }

        $shipping = $request->except(['_token', 'emails', 'message']);
        $pdf = Pdf::loadView('documents.pdfs.shipping_instructions', [
            'contract' => $contract,
            'shipping' => $shipping
        ]);

        $fileName = 'Instructivo_Embarque_' . $contract->contract_number . '.pdf';

        foreach ($emails as $email) {
            \Mail::send('emails.document_certificate', [
                'contract' => $contract,
                'message' => $request->input('message', 'Adjunto Instructivo de Embarque - Contrato ' . $contract->contract_number),
            ], function($mail) use ($email, $fileName, $pdf, $contract) {
                $mail->to($email)
                    ->subject('Instructivo de Embarque - Contrato ' . $contract->contract_number)
                    ->attachData($pdf->output(), $fileName, ['mime' => 'application/pdf']);
            });
        }

        return back()->with('success', 'Instructivo de Embarque enviado exitosamente a ' . count($emails) . ' destinatario(s)');
    }

    /**
     * Listar contratos disponibles para Instructivo de Transporte
     */
    public function listTransportInstructions()
    {
        $contracts = Contract::with('client')
            ->whereIn('status', ['active', 'completed'])
            ->latest()
            ->get();
        
        return view('documents.transport_instructions_list', compact('contracts'));
    }

    /**
     * Mostrar formulario para editar/crear Instructivo de Transporte
     */
    public function editTransportInstructions($contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $transport = [
            'emission_date' => now()->format('Y-m-d'),
            'transport_company' => 'Cotrans Ltda.',
            'contact_info' => 'cotrans.ingrid@gmail.com',
            'client_name' => 'Cofrupa',
            'client_reference' => $contract->contract_number,
            'booking_number' => $contract->booking_number ?? '',
            'shipping_company' => 'MSC',
            'vessel_name' => $contract->vessel_name ?? '',
            'container_type_quantity' => $contract->container_number ?? '1 X 20\' DRY ST',
            'product_quantity' => $contract->product_description ?? '',
            'net_weight_per_unit' => $contract->stock_committed ? ($contract->stock_committed . ' KG') : '',
            'gross_weight_per_unit' => $contract->stock_committed ? (($contract->stock_committed * 1.05) . ' KG') : '',
            'empty_pickup_location' => 'Medlog - Valparaíso',
            'loading_port' => 'VALPARAISO, CHILE',
            'destination_port' => $contract->destination_port ?? '',
            'loading_location' => 'Planta Agrícola Siemel Ltda.',
            'loading_address' => 'Camino Padre Hurtado 3621, Buin',
            'presentation_date' => $contract->etd_date ?? now()->format('Y-m-d'),
            'presentation_time' => '08:00',
            'delivery_terminal' => 'Terminal Pacífico Sur',
            'delivery_port' => 'VALPARAISO, CHILE',
            'official_stacking' => '15-11/19-11',
            'stacking_schedule' => '08:00-15:00',
        ];
        
        return view('documents.transport_instructions_edit', compact('contract', 'transport'));
    }

    /**
     * Guardar Instructivo de Transporte
     */
    public function storeTransportInstructions(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        // Actualizar campos del contrato si se proporcionaron
        $this->updateContractFields($contract, $request);
        
        $transport = [
            'emission_date' => $request->input('emission_date'),
            'transport_company' => $request->input('transport_company'),
            'contact_info' => $request->input('contact_info'),
            'client_name' => $request->input('client_name'),
            'client_reference' => $request->input('client_reference'),
            'booking_number' => $request->input('booking_number'),
            'shipping_company' => $request->input('shipping_company'),
            'vessel_name' => $request->input('vessel_name'),
            'container_type_quantity' => $request->input('container_type_quantity'),
            'product_quantity' => $request->input('product_quantity'),
            'net_weight_per_unit' => $request->input('net_weight_per_unit'),
            'gross_weight_per_unit' => $request->input('gross_weight_per_unit'),
            'empty_pickup_location' => $request->input('empty_pickup_location'),
            'loading_port' => $request->input('loading_port'),
            'destination_port' => $request->input('destination_port'),
            'loading_location' => $request->input('loading_location'),
            'loading_address' => $request->input('loading_address'),
            'presentation_date' => $request->input('presentation_date'),
            'presentation_time' => $request->input('presentation_time'),
            'delivery_terminal' => $request->input('delivery_terminal'),
            'delivery_port' => $request->input('delivery_port'),
            'official_stacking' => $request->input('official_stacking'),
            'stacking_schedule' => $request->input('stacking_schedule'),
        ];

        // Generar PDF
        $pdf = Pdf::loadView('documents.pdfs.transport_instructions', [
            'contract' => $contract->fresh(),
            'transport' => $transport
        ]);

        // Guardar
        $fileName = 'Instructivo_Transporte_' . $contract->contract_number . '_' . now()->format('YmdHis') . '.pdf';
        $folderPath = 'contracts/' . $contract->contract_number;
        $filePath = $folderPath . '/' . $fileName;
        
        Storage::disk('public')->put($filePath, $pdf->output());

        ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => 'instructivo_transporte',
            'document_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('exportations.index')
            ->with('success', 'Instructivo de Transporte generado y guardado. El contrato ha sido actualizado con los nuevos datos.');
    }

    /**
     * Preview Instructivo de Transporte
     */
    public function previewTransportInstructions(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $transport = [
            'emission_date' => $request->input('emission_date'),
            'transport_company' => $request->input('transport_company'),
            'contact_info' => $request->input('contact_info'),
            'client_name' => $request->input('client_name'),
            'client_reference' => $request->input('client_reference'),
            'booking_number' => $request->input('booking_number'),
            'shipping_company' => $request->input('shipping_company'),
            'vessel_name' => $request->input('vessel_name'),
            'container_type_quantity' => $request->input('container_type_quantity'),
            'product_quantity' => $request->input('product_quantity'),
            'net_weight_per_unit' => $request->input('net_weight_per_unit'),
            'gross_weight_per_unit' => $request->input('gross_weight_per_unit'),
            'empty_pickup_location' => $request->input('empty_pickup_location'),
            'loading_port' => $request->input('loading_port'),
            'destination_port' => $request->input('destination_port'),
            'loading_location' => $request->input('loading_location'),
            'loading_address' => $request->input('loading_address'),
            'presentation_date' => $request->input('presentation_date'),
            'presentation_time' => $request->input('presentation_time'),
            'delivery_terminal' => $request->input('delivery_terminal'),
            'delivery_port' => $request->input('delivery_port'),
            'official_stacking' => $request->input('official_stacking'),
            'stacking_schedule' => $request->input('stacking_schedule'),
        ];

        $pdf = Pdf::loadView('documents.pdfs.transport_instructions', [
            'contract' => $contract,
            'transport' => $transport
        ]);

        return $pdf->stream('Preview_Instructivo_Transporte_' . $contract->contract_number . '.pdf');
    }

    /**
     * Enviar Instructivo de Transporte por email
     */
    public function sendTransportInstructions(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $emails = preg_split('/[,;]+/', $request->input('emails', ''));
        $emails = array_map('trim', $emails);
        $emails = array_filter($emails, fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL));

        if (empty($emails)) {
            return back()->with('error', 'No se proporcionaron emails válidos');
        }

        $transport = [
            'emission_date' => $request->input('emission_date'),
            'transport_company' => $request->input('transport_company'),
            'contact_info' => $request->input('contact_info'),
            'client_name' => $request->input('client_name'),
            'client_reference' => $request->input('client_reference'),
            'booking_number' => $request->input('booking_number'),
            'shipping_company' => $request->input('shipping_company'),
            'vessel_name' => $request->input('vessel_name'),
            'container_type_quantity' => $request->input('container_type_quantity'),
            'product_quantity' => $request->input('product_quantity'),
            'net_weight_per_unit' => $request->input('net_weight_per_unit'),
            'gross_weight_per_unit' => $request->input('gross_weight_per_unit'),
            'empty_pickup_location' => $request->input('empty_pickup_location'),
            'loading_port' => $request->input('loading_port'),
            'destination_port' => $request->input('destination_port'),
            'loading_location' => $request->input('loading_location'),
            'loading_address' => $request->input('loading_address'),
            'presentation_date' => $request->input('presentation_date'),
            'presentation_time' => $request->input('presentation_time'),
            'delivery_terminal' => $request->input('delivery_terminal'),
            'delivery_port' => $request->input('delivery_port'),
            'official_stacking' => $request->input('official_stacking'),
            'stacking_schedule' => $request->input('stacking_schedule'),
        ];
        
        $pdf = Pdf::loadView('documents.pdfs.transport_instructions', [
            'contract' => $contract,
            'transport' => $transport
        ]);

        $fileName = 'Instructivo_Transporte_' . $contract->contract_number . '.pdf';

        foreach ($emails as $email) {
            \Mail::send('emails.document_certificate', [
                'contract' => $contract,
                'message' => $request->input('message', 'Adjunto Instructivo de Transporte - Contrato ' . $contract->contract_number),
            ], function($mail) use ($email, $fileName, $pdf, $contract) {
                $mail->to($email)
                    ->subject('Instructivo de Transporte - Contrato ' . $contract->contract_number)
                    ->attachData($pdf->output(), $fileName, ['mime' => 'application/pdf']);
            });
        }

        return back()->with('success', 'Instructivo de Transporte enviado exitosamente a ' . count($emails) . ' destinatario(s)');
    }

    /**
     * Listar contratos disponibles para Guías de Despacho
     */
    public function listDispatchGuides()
    {
        $contracts = Contract::with('client')
            ->whereIn('status', ['active', 'completed'])
            ->latest()
            ->get();
        
        return view('documents.dispatch_guides_list', compact('contracts'));
    }

    /**
     * Editar Guía de Despacho
     */
    public function editDispatchGuide($contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        return view('documents.dispatch_guide_edit', compact('contract'));
    }

    /**
     * Guardar Guía de Despacho
     */
    public function storeDispatchGuide(Request $request, $contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        
        $validated = $request->validate([
            'dispatch_date' => 'required|date',
            'dispatch_number' => 'required|string|max:255',
            'origin_location' => 'required|string',
            'client_name' => 'required|string',
            'consignee' => 'nullable|string',
            'product_description' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'packing' => 'nullable|string',
            'destination' => 'required|string',
            'vessel' => 'nullable|string',
            'notes' => 'nullable|string',
            // Campos del contrato
            'contract_product_description' => 'nullable|string',
            'contract_packing' => 'nullable|string|max:255',
            'contract_crop_year' => 'nullable|string|max:255',
            'contract_quality_specification' => 'nullable|string',
            'contract_humidity' => 'nullable|string|max:255',
            'contract_total_defects' => 'nullable|string|max:255',
        ]);

        // Actualizar campos del contrato si se proporcionaron
        $this->updateContractFields($contract, $request);

        // Generar PDF (implementar cuando se tenga la vista PDF)
        // $pdf = Pdf::loadView('documents.pdfs.dispatch_guide', [
        //     'contract' => $contract->fresh(),
        //     'dispatch' => $validated
        // ]);

        // Guardar documento
        // ContractDocument::create([...]);

        return redirect()->route('exportations.index')
            ->with('success', 'Guía de Despacho guardada. El contrato ha sido actualizado con los nuevos datos.');
    }

    /**
     * Listar contratos disponibles para Factura
     */
    public function listInvoices()
    {
        $contracts = Contract::with('client')
            ->whereIn('status', ['active', 'completed'])
            ->latest()
            ->get();
        
        return view('documents.invoice_list', compact('contracts'));
    }

    /**
     * Editar Factura
     */
    public function editInvoice($contractId)
    {
        $contract = Contract::with('client')->findOrFail($contractId);
        return view('documents.invoice_edit', compact('contract'));
    }

    /**
     * Guardar Factura
     */
    public function storeInvoice(Request $request, $contractId)
    {
        // Implementar según necesidades
        return redirect()->route('exportations.index')
            ->with('success', 'Factura generada');
    }
}

