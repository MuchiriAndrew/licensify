<?php

namespace App\Filament\Resources\LicenseResource\Pages;

use Filament\Resources\Pages\ListRecords as BaseListRecords;
use App\Filament\Resources\LicenseResource;

class ListLicenses extends BaseListRecords
{
    protected static string $resource = LicenseResource::class;
}