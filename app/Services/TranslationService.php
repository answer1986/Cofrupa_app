<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    /**
     * Translate text from Spanish to English
     * Uses LibreTranslate API (free alternative) or can be configured to use Google Translate
     */
    public static function translateToEnglish($text)
    {
        if (empty($text)) {
            return '';
        }

        try {
            // Option 1: Use LibreTranslate (free, no API key required)
            // You can host your own instance or use a public one
            $response = Http::timeout(10)->post('https://libretranslate.de/translate', [
                'q' => $text,
                'source' => 'es',
                'target' => 'en',
                'format' => 'text'
            ]);

            if ($response->successful() && isset($response->json()['translatedText'])) {
                return $response->json()['translatedText'];
            }

            // Fallback: Simple word-by-word translation for common terms
            return self::simpleTranslate($text);
        } catch (\Exception $e) {
            Log::warning('Translation failed: ' . $e->getMessage());
            // Fallback to simple translation
            return self::simpleTranslate($text);
        }
    }

    /**
     * Simple translation for common contract terms
     * This is a fallback when API is not available
     */
    private static function simpleTranslate($text)
    {
        // Common translations dictionary
        $translations = [
            'Vendedor' => 'Seller',
            'Fecha' => 'Date',
            'Contrato' => 'Contract',
            'Referencia' => 'Reference',
            'Consignatario' => 'Consignee',
            'Notificar' => 'Notify',
            'Contacto' => 'Contact',
            'Puerto de destino' => 'Port of destination',
            'Puerto de carga' => 'Port of charge',
            'Producto' => 'Product',
            'Empaque' => 'Packing',
            'Monto total' => 'Total amount',
            'Pago por contenedor' => 'Payment per container',
            'Calidad' => 'Quality',
            'Humedad' => 'Humidity',
            'Defectos totales' => 'Total Defects',
            'Documentos' => 'Documents',
            'Beneficiario' => 'Beneficiary',
            'Cuenta bancaria' => 'Bank Account',
            'Número de cuenta' => 'Account Number',
            'Detalles comerciales' => 'Commercial Details',
        ];

        // Simple replacement for known terms
        $translated = $text;
        foreach ($translations as $spanish => $english) {
            $translated = str_ireplace($spanish, $english, $translated);
        }

        return $translated;
    }

    /**
     * Translate multiple fields at once
     */
    public static function translateContractFields($data)
    {
        $fieldsToTranslate = [
            'product_description' => 'product_description_english',
            'quality_specification' => 'quality_specification_english',
            'packing' => 'packing_english',
            'seller_address' => 'seller_address_english',
            'consignee_address' => 'consignee_address_english',
            'notify_address' => 'notify_address_english',
            'payment_terms' => 'payment_terms_english',
            'required_documents' => 'required_documents_english',
            'transportation_details' => 'transportation_details_english',
            'shipment_schedule' => 'shipment_schedule_english',
            'contract_clause' => 'contract_clause_english',
            'commercial_details' => 'commercial_details_english',
        ];

        foreach ($fieldsToTranslate as $spanishField => $englishField) {
            if (!empty($data[$spanishField])) {
                $data[$englishField] = self::translateToEnglish($data[$spanishField]);
            }
        }

        return $data;
    }
}
