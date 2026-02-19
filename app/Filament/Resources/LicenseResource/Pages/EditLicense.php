<?php

namespace App\Filament\Resources\LicenseResource\Pages;

use Filament\Resources\Pages\EditRecord as BaseEditRecord;
use App\Filament\Resources\LicenseResource;

class EditLicense extends BaseEditRecord
{
    protected static string $resource = LicenseResource::class;
}