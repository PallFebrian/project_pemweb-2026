<?php

namespace App\Filament\Admin\Resources\OrderResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class KomplainPelanggansRelationManager extends RelationManager
{
    protected static string $relationship = 'komplainPelanggans';

    protected static ?string $title = 'Komplain Pelanggan';

    protected static ?string $recordTitleAttribute = 'isi_komplain';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal_komplain', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('isi_komplain')
                    ->label('Isi Komplain')
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\TextColumn::make('status_komplain')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'baru' => 'Baru',
                            'diproses' => 'Diproses',
                            'selesai' => 'Selesai',
                            'ditolak' => 'Ditolak',
                            default => $state,
                        }
                    )
                    ->color(
                        fn (string $state): string => match ($state) {
                            'baru' => 'warning',
                            'diproses' => 'info',
                            'selesai' => 'success',
                            'ditolak' => 'danger',
                            default => 'gray',
                        }
                    ),

                Tables\Columns\TextColumn::make('adminPenangan.name')
                    ->label('Ditangani Oleh')
                    ->placeholder('Belum ditangani'),

                Tables\Columns\TextColumn::make('tanggapan_admin')
                    ->label('Tanggapan Admin')
                    ->limit(50)
                    ->placeholder('Belum ada tanggapan')
                    ->wrap(),

                Tables\Columns\TextColumn::make('tanggal_komplain')
                    ->label('Tanggal Komplain')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
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