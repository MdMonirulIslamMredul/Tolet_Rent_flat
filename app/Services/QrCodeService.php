<?php

namespace App\Services;

use App\Models\Rental\Building;

class QrCodeService
{
    /**
     * Get the public URL for building QR scan
     */
    public static function getBuildingUrl(Building|string $building): string
    {
        $buildingId = is_string($building) ? $building : $building->building_id;
        return url('/b/' . $buildingId);
    }

    /**
     * Generate an SVG/Image QR code URL
     */
    public static function getQrImageUrl(string $data, int $size = 300): string
    {
        $encoded = urlencode($data);
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&margin=10&data={$encoded}";
    }

    /**
     * Generate an SVG data URI or standard SVG string for instant offline fallback
     */
    public static function getInlineQrSvg(string $data, int $size = 250): string
    {
        return self::generateSvg($data, $size);
    }

    /**
     * Generate an SVG/image element for view embedding
     */
    public static function generateSvg(string $data, int $size = 200): string
    {
        $url = self::getQrImageUrl($data, $size);
        return '<img src="' . htmlspecialchars($url) . '" alt="QR Code" width="' . $size . '" height="' . $size . '" class="img-fluid" />';
    }
}

