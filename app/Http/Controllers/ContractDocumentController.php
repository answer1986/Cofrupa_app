<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\ContractDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ContractDocumentController extends Controller
{
    /**
     * Upload a document for a contract
     */
    public function upload(Request $request, $contractId)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'file' => 'required|file|max:20480', // Max 20MB
            'notes' => 'nullable|string',
        ]);

        $contract = Contract::findOrFail($contractId);
        $file = $request->file('file');

        // Create directory structure: contracts/{contract_id}/{document_type}/
        $directoryPath = "contracts/{$contract->id}/{$validated['document_type']}";
        $filePath = $file->store($directoryPath, 'public');

        $document = ContractDocument::create([
            'contract_id' => $contract->id,
            'document_type' => $validated['document_type'],
            'document_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'notes' => $validated['notes'] ?? null,
            'uploaded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Documento subido exitosamente.');
    }

    /**
     * Download a document
     */
    public function download($id)
    {
        $document = ContractDocument::findOrFail($id);
        
        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'El archivo no existe.');
        }

        return Storage::disk('public')->download($document->file_path, $document->document_name);
    }

    /**
     * Delete a document
     */
    public function destroy($id)
    {
        $document = ContractDocument::findOrFail($id);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Documento eliminado exitosamente.');
    }

    /**
     * Get documents by type for a contract
     */
    public function getByType($contractId, $type)
    {
        $documents = ContractDocument::where('contract_id', $contractId)
            ->where('document_type', $type)
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($documents);
    }

    /**
     * Generate quality certificate
     */
    public function generateQualityCertificate($contractId)
    {
        $contract = Contract::with(['client', 'broker'])->findOrFail($contractId);
        
        // This will be implemented with PDF generation
        return view('contracts.documents.quality_certificate', compact('contract'));
    }
}
