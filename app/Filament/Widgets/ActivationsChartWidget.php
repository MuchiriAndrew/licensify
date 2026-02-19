<?php

namespace App\Filament\Widgets;

use App\Models\License;
use App\Models\LicenseActivation;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Carbon;

class ActivationsChartWidget extends LineChartWidget
{
    protected static ?string $heading = 'Validations over the last 14 days';

    protected static ?string $maxHeight = '200px';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $licenseIds = License::query()
            ->where('user_id', auth()->id())
            ->pluck('id');

        $days = collect(range(13, 0))->map(fn (int $daysAgo) => Carbon::today()->subDays($daysAgo));
        $labels = $days->map(fn (Carbon $date) => $date->format('M j'))->values()->all();

        $successData = $days->map(function (Carbon $date) use ($licenseIds) {
            return LicenseActivation::query()
                ->whereIn('license_id', $licenseIds)
                ->where('success', true)
                ->whereDate('validated_at', $date)
                ->count();
        })->values()->all();

        $failureData = $days->map(function (Carbon $date) use ($licenseIds) {
            return LicenseActivation::query()
                ->whereIn('license_id', $licenseIds)
                ->where('success', false)
                ->whereDate('validated_at', $date)
                ->count();
        })->values()->all();

        return [
            'datasets' => [
                [
                    'label' => 'Successful',
                    'data' => $successData,
                ],
                [
                    'label' => 'Failed',
                    'data' => $failureData,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
