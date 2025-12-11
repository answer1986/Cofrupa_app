<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QrCodeService
{
    /**
     * Generate QR code with Cofrupa logo
     *
     * @param string $data The data to encode in QR
     * @param string $filename Optional filename
     * @return string Path to the generated QR code image
     */
    public function generateWithLogo($data, $filename = null)
    {
        if (!$filename) {
            $filename = 'qrcode_' . Str::random(10) . '.svg';
        } else {
            // Ensure .svg extension
            $filename = preg_replace('/\.(png|jpg|jpeg)$/i', '.svg', $filename);
        }

        // Generate QR code using SVG format (doesn't require imagick)
        $qrCode = QrCode::format('svg')
            ->size(400)
            ->errorCorrection('H')
            ->generate($data);

        // Save to storage
        $path = 'qrcodes/' . $filename;
        Storage::disk('public')->put($path, $qrCode);

        return $path;
    }

    /**
     * Generate QR code without logo (for plain QR codes)
     *
     * @param string $data The data to encode
     * @param string $filename Optional filename
     * @return string Path to the generated QR code image
     */
    public function generatePlain($data, $filename = null)
    {
        if (!$filename) {
            $filename = 'qrcode_' . Str::random(10) . '.svg';
        } else {
            // Ensure .svg extension
            $filename = preg_replace('/\.(png|jpg|jpeg)$/i', '.svg', $filename);
        }

        // Generate QR code using SVG format (doesn't require imagick)
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($data);

        $path = 'qrcodes/' . $filename;
        Storage::disk('public')->put($path, $qrCode);

        return $path;
    }

    /**
     * Generate QR code as base64 string with logo
     *
     * @param string $data The data to encode
     * @return string Base64 encoded QR code
     */
    public function generateBase64WithLogo($data)
    {
        // Generate QR code using SVG format (doesn't require imagick)
        $svg = QrCode::format('svg')
            ->size(400)
            ->errorCorrection('H')
            ->generate($data);
        
        // Return SVG as base64
        return base64_encode($svg);
    }

    /**
     * Generate QR code as base64 string without logo
     *
     * @param string $data The data to encode
     * @return string Base64 encoded QR code
     */
    public function generateBase64Plain($data)
    {
        // Generate QR code using SVG format (doesn't require imagick)
        $svg = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($data);
        
        // Return SVG as base64
        return base64_encode($svg);
    }
}