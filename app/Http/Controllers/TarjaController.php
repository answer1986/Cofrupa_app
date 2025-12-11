<?php

namespace App\Http\Controllers;

use App\Models\ProcessedBin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TarjaController extends Controller
{
    /**
     * Display the tarja (label) for printing
     */
    public function show($id)
    {
        $processedBin = ProcessedBin::with('supplier')->findOrFail($id);
        
        if (!$processedBin->tarja_number) {
            return redirect()->back()->with('error', 'Esta tarja no tiene número asignado.');
        }
        
        return view('tarjas.show', compact('processedBin'));
    }
    
    /**
     * Print tarja (same as show but optimized for printing)
     */
    public function print($id)
    {
        $processedBin = ProcessedBin::with('supplier')->findOrFail($id);
        
        if (!$processedBin->tarja_number) {
            return redirect()->back()->with('error', 'Esta tarja no tiene número asignado.');
        }
        
        return view('tarjas.print', compact('processedBin'));
    }
    
    /**
     * Show QR scanner page
     */
    public function scanner()
    {
        return view('tarjas.scanner');
    }
    
    /**
     * Read QR code data and display expanded information
     */
    public function readQr(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);
        
        try {
            // Decrypt the QR data
            $decrypted = Crypt::decrypt($request->qr_data);
            $data = json_decode($decrypted, true);
            
            if (!is_array($data) || !isset($data['id']) || !isset($data['type'])) {
                return redirect()->route('tarjas.scanner')
                    ->with('error', 'Código QR inválido o no pertenece a este sistema.');
            }
            
            // Validate QR type
            if ($data['type'] !== 'tarja_internal') {
                return redirect()->route('tarjas.scanner')
                    ->with('error', 'Este código QR no es de una tarja válida.');
            }
            
            // Load the processed bin with all relationships
            $processedBin = ProcessedBin::with(['supplier'])
                ->find($data['id']);
            
            if (!$processedBin) {
                return redirect()->route('tarjas.scanner')
                    ->with('error', 'Tarja no encontrada en el sistema.');
            }
            
            // Redirect to expanded view
            return redirect()->route('tarjas.expanded', $processedBin->id)
                ->with('success', 'Código QR leído correctamente.');
                
        } catch (\Exception $e) {
            return redirect()->route('tarjas.scanner')
                ->with('error', 'Error al leer el código QR: ' . $e->getMessage());
        }
    }
    
    /**
     * Display expanded information from QR code
     */
    public function expanded($id)
    {
        $processedBin = ProcessedBin::with(['supplier'])->findOrFail($id);
        
        return view('tarjas.expanded', compact('processedBin'));
    }
}
