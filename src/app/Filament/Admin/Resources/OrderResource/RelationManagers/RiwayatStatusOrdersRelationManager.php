<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RiwayatStatusOrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'riwayatStatusOrders';

    protected static ?string $title = 'Riwayat Status';

    protected static ?string $recordTitleAttribute = 'status';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('waktu_status', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            \App\Models\Order::labelStatus($state)
                    )
                    ->badge(),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->wrap(),

                Tables\Columns\TextColumn::make('pengubah.name')
                    ->label('Diubah Oleh')
                    ->default('Sistem'),

                Tables\Columns\TextColumn::make('waktu_status')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}