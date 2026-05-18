<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LogStatusPermintaanResource\Pages;
use App\Models\LogStatusPermintaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class LogStatusPermintaanResource extends Resource
{
    protected static ?string $model = LogStatusPermintaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Transaksi Layanan';

    protected static ?string $navigationLabel = 'Log Status Permintaan';

    protected static ?string $modelLabel = 'Log Status Permintaan';

    protected static ?string $pluralModelLabel = 'Log Status Permintaan';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Log Status')
                    ->schema([
                        Forms\Components\TextInput::make('permintaanLayanan.kode')
                            ->label('Kode Request')
                            ->disabled(),

                        Forms\Components\TextInput::make('user.name')
                            ->label('Diubah Oleh')
                            ->disabled(),

                        Forms\Components\TextInput::make('status_lama')
                            ->label('Status Lama')
                            ->disabled(),

                        Forms\Components\TextInput::make('status_baru')
                            ->label('Status Baru')
                            ->disabled(),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(4)
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('permintaanLayanan.kode')
                    ->label('Kode Request')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Diubah Oleh')
                    ->default('Sistem')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_lama')
                    ->label('Status Lama')
                    ->badge()
                    ->default('-')
                    ->color(fn (?string $state): string => match ($state) {
                        'baru' => 'info',
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : '-'),

                Tables\Columns\TextColumn::make('status_baru')
                    ->label('Status Baru')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'baru' => 'info',
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : '-'),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(45)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_baru')
                    ->label('Status Baru')
                    ->options([
                        'baru' => 'Baru',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogStatusPermintaans::route('/'),
        ];
    }
}