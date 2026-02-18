<?php

namespace App\Filament\Resources\LicenseResource\Pages;

use App\Filament\Resources\LicenseResource;
use App\Models\License;
use App\Services\LicenseValidationService;
use Filament\Resources\Pages\CreateRecord as BaseCreateRecord;

class CreateRecord extends BaseCreateRecord
{
    protected static string $resource = LicenseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty(trim($data['key'] ?? ''))) {
            do {
                $data['key'] = LicenseValidationService::generateLicenseKey();
            } while (License::where('key', $data['key'])->exists());
        }

        return $data;
    }
}
