<?php

namespace App\Services;

class LicenseValidationService
{
    /**
     * Normalize domain for consistent comparison.
     * Strips protocol, path, port, and normalizes www.
     */
    public static function normalizeDomain(string $domain): string
    {
        $domain = strtolower(trim($domain));

        // Strip protocol
        $domain = preg_replace('#^https?://#', '', $domain);

        // Strip path and query string
        $domain = explode('/', $domain)[0];
        $domain = explode('?', $domain)[0];

        // Strip port
        if (str_contains($domain, ':')) {
            $domain = explode(':', $domain)[0];
        }

        // Optional: treat www.example.com same as example.com
        if (str_starts_with($domain, 'www.')) {
            $domain = substr($domain, 4);
        }

        return $domain;
    }

    /**
     * Generate a secure license key in format XXXX-XXXX-XXXX-XXXX.
     */
    public static function generateLicenseKey(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Exclude confusing chars: 0,O,1,I
        $segments = [];

        for ($i = 0; $i < 4; $i++) {
            $segment = '';
            for ($j = 0; $j < 4; $j++) {
                $segment .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $segments[] = $segment;
        }

        return implode('-', $segments);
    }
}
