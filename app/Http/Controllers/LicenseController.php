<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateLicenseRequest;
use App\Models\License;
use App\Models\LicenseActivation;
use App\Services\LicenseValidationService;

class LicenseController extends Controller
{
    public function validate_license(ValidateLicenseRequest $request)
    {
        $licenseKey = $request->input('license_key');
        $domain = LicenseValidationService::normalizeDomain($request->input('domain'));

        $license = License::where('key', $licenseKey)->first();

        if (!$license) {
            $this->logActivation(null, $licenseKey, $domain, $request, false, 'invalid_key');
            return $this->validationFailed();
        }

        if (!$license->is_active || ($license->expires_at && $license->expires_at->isPast())) {
            $this->logActivation($license->id, $licenseKey, $domain, $request, false, 'expired_or_inactive');
            return $this->validationFailed();
        }

        $storedDomain = $license->domain ? LicenseValidationService::normalizeDomain($license->domain) : null;

        if ($storedDomain && $storedDomain !== $domain) {
            $this->logActivation($license->id, $licenseKey, $domain, $request, false, 'domain_mismatch');
            return $this->validationFailed();
        }

        if (!$license->domain) {
            $license->domain = $domain;
            $license->save();
        }

        $this->logActivation($license->id, $licenseKey, $domain, $request, true);

        return response()->json(['status' => 'success', 'message' => 'License validation successful']);
    }

    private function validationFailed()
    {
        return response()->json(['status' => 'error', 'message' => 'License validation failed'], 403);
    }

    private function logActivation(
        ?int $licenseId,
        string $licenseKey,
        string $domain,
        ValidateLicenseRequest $request,
        bool $success,
        ?string $failureReason = null
    ): void {
        LicenseActivation::create([
            'license_id' => $licenseId,
            'domain' => $domain,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'success' => $success,
            'failure_reason' => $failureReason,
            'validated_at' => now(),
        ]);
    }
}
