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
                ->unique(ignoreRecord: true)
                ->disabledOn('edit')
                ->helperText('The unique key your customers use to activate the product. Leave blank and one will be generated when you save. Cannot be changed after creation.'),
            Forms\Components\TextInput::make('domain')
                ->nullable()
                ->helperText('Optional: Restrict this license to a specific domain (e.g. example.com). Leave empty to allow use on any domain.'),
            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true)
                ->helperText('Inactive licenses will fail validation. Turn off to revoke access without deleting the license.'),
            Forms\Components\DatePicker::make('expires_at')
                ->label('Expires At')
                ->nullable()
                ->helperText('Optional: When this license should stop working. Leave empty for no expiry (perpetual license).'),
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
            'index' => Pages\ListLicenses::route('/'),
            'create' => Pages\CreateLicense::route('/create'),
            'edit' => Pages\EditLicense::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ActivationsRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}
