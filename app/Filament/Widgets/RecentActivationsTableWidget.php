<?php

namespace App\Filament\Widgets;

use App\Models\License;
use App\Models\LicenseActivation;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentActivationsTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent validations';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getTableQuery(): Builder
    {
        $licenseIds = License::query()
            ->where('user_id', auth()->id())
            ->pluck('id');

        return LicenseActivation::query()
            ->whereIn('license_id', $licenseIds)
            ->orderBy('validated_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('domain')
                ->label('Domain')
                ->searchable(),
            Tables\Columns\TextColumn::make('ip')
                ->label('IP')
                ->placeholder('—'),
            Tables\Columns\IconColumn::make('success')
                ->label('Result')
                ->boolean()
                ->trueIcon('heroicon-s-check-circle')
                ->falseIcon('heroicon-s-x-circle')
                ->trueColor('success')
                ->falseColor('danger'),
            Tables\Columns\TextColumn::make('failure_reason')
                ->label('Reason')
                ->placeholder('—')
                ->limit(30),
            Tables\Columns\TextColumn::make('validated_at')
                ->label('When')
                ->dateTime()
                ->sortable(),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10, 25];
    }

    protected function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }
}
