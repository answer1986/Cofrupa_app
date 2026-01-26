<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Contract;
use App\Models\ShippingLine;
use App\Models\LogisticsCompany;
use App\Mail\TransportShipmentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with(['contract.client', 'contract.broker', 'shippingLine', 'stages'])
            ->latest()
            ->paginate(15);
        
        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        // Cargar todos los contratos que no estén cancelados
        $contracts = Contract::with('client')
            ->whereIn('status', ['draft', 'active', 'completed'])
            ->orderBy('id', 'desc')
            ->get();
        $shippingLines = ShippingLine::where('is_active', true)->get();
        $logisticsCompanies = LogisticsCompany::where('is_active', true)->get();
        
        return view('shipments.create', compact('contracts', 'shippingLines', 'logisticsCompanies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'shipping_line_id' => 'nullable|exists:shipping_lines,id',
            'scheduled_date' => 'required|date',
            'plant_pickup_company' => 'nullable|string|max:255',
            'customs_loading_company' => 'nullable|string|max:255',
            'transport_company_id' => 'nullable|exists:logistics_companies,id',
            'transport_company' => 'nullable|string|max:255',
            'transport_contact' => 'nullable|string|max:255',
            'transport_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'transport_email' => 'nullable|email:rfc,dns',
            'transport_request_number' => 'nullable|string|max:255',
            'truck_cost' => 'nullable|numeric|min:0',
            'plant_pickup_scheduled' => 'nullable|date',
            'customs_loading_scheduled' => 'nullable|date',
            'transport_departure_scheduled' => 'nullable|date',
            'port_arrival_scheduled' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Generar número de despacho único
        $validated['shipment_number'] = 'DESP-' . str_pad(Shipment::max('id') + 1, 6, '0', STR_PAD_LEFT);
        $validated['status'] = 'scheduled';

        $shipment = Shipment::create($validated);

        return redirect()->route('shipments.edit', $shipment->id)->with('success', 'Despacho creado exitosamente. Puede enviar el correo a la empresa de transporte desde aquí.');
    }

    public function show($id)
    {
        $shipment = Shipment::with([
            'contract.client',
            'contract.broker',
            'shippingLine',
            'stages',
            'documents',
            'exportations'
        ])->findOrFail($id);
        
        return view('shipments.show', compact('shipment'));
    }

    public function edit($id)
    {
        $shipment = Shipment::findOrFail($id);
        $contracts = Contract::with('client')->whereIn('status', ['draft', 'active', 'completed'])->get();
        $shippingLines = ShippingLine::where('is_active', true)->get();
        $logisticsCompanies = LogisticsCompany::where('is_active', true)->get();
        
        return view('shipments.edit', compact('shipment', 'contracts', 'shippingLines', 'logisticsCompanies'));
    }

    public function update(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);
        
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'shipping_line_id' => 'nullable|exists:shipping_lines,id',
            'scheduled_date' => 'required|date',
            'actual_date' => 'nullable|date',
            'status' => 'required|in:scheduled,in_transit,at_customs,loaded,shipped,completed,cancelled',
            'plant_pickup_company' => 'nullable|string|max:255',
            'customs_loading_company' => 'nullable|string|max:255',
            'transport_company_id' => 'nullable|exists:logistics_companies,id',
            'transport_company' => 'nullable|string|max:255',
            'transport_contact' => 'nullable|string|max:255',
            'transport_phone' => 'nullable|regex:/^\+[0-9]{11}$/',
            'transport_email' => 'nullable|email:rfc,dns',
            'transport_request_number' => 'nullable|string|max:255',
            'truck_cost' => 'nullable|numeric|min:0',
            'plant_pickup_scheduled' => 'nullable|date',
            'plant_pickup_actual' => 'nullable|date',
            'customs_loading_scheduled' => 'nullable|date',
            'customs_loading_actual' => 'nullable|date',
            'transport_departure_scheduled' => 'nullable|date',
            'transport_departure_actual' => 'nullable|date',
            'port_arrival_scheduled' => 'nullable|date',
            'port_arrival_actual' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $shipment->update($validated);

        return redirect()->route('shipments.index')->with('success', 'Despacho actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $shipment = Shipment::findOrFail($id);
        $shipment->delete();

        return redirect()->route('shipments.index')->with('success', 'Despacho eliminado exitosamente.');
    }

    public function sendTransportEmail(Request $request, $id)
    {
        $shipment = Shipment::with(['contract.client', 'contract.broker', 'shippingLine'])->findOrFail($id);
        
        $request->validate([
            'transport_email' => 'required|email:rfc,dns',
        ]);

        try {
            Mail::to($request->transport_email)->send(new TransportShipmentMail($shipment));
            
            // Actualizar el email en el shipment si no estaba guardado
            if (!$shipment->transport_email) {
                $shipment->update(['transport_email' => $request->transport_email]);
            }
            
            return back()->with('success', 'Correo enviado exitosamente a ' . $request->transport_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }
}
