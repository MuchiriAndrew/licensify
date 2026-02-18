<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LicenseResource\Pages;
use App\Filament\Resources\LicenseResource\RelationManagers;
use App\Models\License;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;

class LicenseResource extends Resource
{
    protected static ?string $model = License::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->label('License Key')
                ->placeholder('Leave empty to auto-generate')
                ->required(false)
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('domain')
                ->nullable(),
            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),
            Forms\Components\DatePicker::make('expires_at')
                ->label('Expires At')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('key')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('domain'),
            Tables\Columns\BooleanColumn::make('is_active')->label('Active'),
            Tables\Columns\TextColumn::make('expires_at')->label('Expires At'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
            'edit' => Pages\EditRecord::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ActivationsRelationManager::class,
        ];
    }
}
