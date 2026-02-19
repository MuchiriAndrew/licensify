<?php

namespace App\Filament\Widgets;

use App\Models\License;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Database\Eloquent\Builder;

class LicenseStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getCards(): array
    {
        $userId = auth()->id();
        $licenseQuery = License::query()->where('user_id', $userId);

        $totalLicenses = (clone $licenseQuery)->count();
        $activeLicenses = (clone $licenseQuery)->where('is_active', true)->count();
        $expiringSoon = (clone $licenseQuery)
            ->where('is_active', true)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays(30)])
            ->count();
        $expiredCount = (clone $licenseQuery)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->count();

        $licenseIds = (clone $licenseQuery)->pluck('id');
        $activationsQuery = \App\Models\LicenseActivation::query()
            ->whereIn('license_id', $licenseIds);
        $validationsLast30 = (clone $activationsQuery)
            ->where('validated_at', '>=', now()->subDays(30));
        $successfulValidations = (clone $validationsLast30)->where('success', true)->count();
        $totalValidations = (clone $validationsLast30)->count();

        return [
            Card::make('Total licenses', $totalLicenses)
                ->description('All license keys you\'ve created')
                ->descriptionIcon('heroicon-s-key')
                ->color('primary'),
            Card::make('Active licenses', $activeLicenses)
                ->description('Currently valid and in use')
                ->descriptionIcon('heroicon-s-check-circle')
                ->color('success'),
            Card::make('Expiring in 30 days', $expiringSoon)
                ->description(
                    $expiringSoon > 0 ? 'Renew or extend soon' : ($expiredCount > 0 ? "{$expiredCount} already expired" : 'None')
                )
                ->color($expiringSoon > 0 ? 'warning' : ($expiredCount > 0 ? 'danger' : null)),
            Card::make('Validations (last 30 days)', $totalValidations > 0 ? "{$successfulValidations} / {$totalValidations} successful" : '0')
                ->description('API validation checks from your apps')
                ->color($totalValidations > 0 ? ($successfulValidations === $totalValidations ? 'success' : 'warning') : null),
        ];
    }
}
