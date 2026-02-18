<?php

namespace App\Filament\Resources\LicenseResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class ActivationsRelationManager extends RelationManager
{
    protected static string $relationship = 'activations';

    protected static ?string $title = 'Activation History';

    protected static ?string $recordTitleAttribute = 'domain';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('domain')->label('Domain'),
                Tables\Columns\TextColumn::make('ip')->label('IP'),
                Tables\Columns\BooleanColumn::make('success')->label('Success'),
                Tables\Columns\TextColumn::make('failure_reason')->label('Reason')->placeholder('—'),
                Tables\Columns\TextColumn::make('validated_at')->label('When')->dateTime()->sortable(),
            ])
            ->defaultSort('validated_at', 'desc')
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
