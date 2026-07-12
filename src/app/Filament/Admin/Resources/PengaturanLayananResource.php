<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PengaturanLayananResource\Pages;
use App\Models\PengaturanLayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengaturanLayananResource extends Resource
{
    protected static ?string $model = PengaturanLayanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Pengaturan Layanan';

    protected static ?string $modelLabel = 'Pengaturan Layanan';

    protected static ?string $pluralModelLabel = 'Pengaturan Layanan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pengaturan Basecamp')
                    ->schema([
                        Forms\Components\TextInput::make('nama_pengaturan')
                            ->label('Nama Pengaturan')
                            ->required()
                            ->default('default')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('titik_awal_basecamp')
                            ->label('Titik Awal Basecamp')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('latitude_basecamp')
                            ->label('Latitude Basecamp')
                            ->numeric()
                            ->placeholder('Contoh: -6.1234567'),

                        Forms\Components\TextInput::make('longitude_basecamp')
                            ->label('Longitude Basecamp')
                            ->numeric()
                            ->placeholder('Contoh: 106.1234567'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pengaturan Biaya')
                    ->schema([
                        Forms\Components\TextInput::make('biaya_flat_satu_km')
                            ->label('Biaya Flat ≤ 1 KM')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(7000),

                        Forms\Components\TextInput::make('biaya_per_km')
                            ->label('Biaya Per KM')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(5000),

                        Forms\Components\TextInput::make('surcharge_express_per_dua_km')
                            ->label('Surcharge Express Per 2 KM')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(10000),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Pengaturan Sistem')
                    ->schema([
                        Forms\Components\Toggle::make('google_maps_api_enabled')
                            ->label('OpenStreetMap dan OSRM Aktif')
                            ->default(true),

                        Forms\Components\TextInput::make('batas_simpan_dokumen_hari')
                            ->label('Batas Simpan Dokumen / Bukti')
                            ->numeric()
                            ->suffix('hari')
                            ->required()
                            ->default(30),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pengaturan')
                    ->label('Nama Pengaturan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('titik_awal_basecamp')
                    ->label('Basecamp')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('biaya_flat_satu_km')
                    ->label('Flat ≤ 1 KM')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('biaya_per_km')
                    ->label('Per KM')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('surcharge_express_per_dua_km')
                    ->label('Express / 2 KM')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\IconColumn::make('google_maps_api_enabled')
                    ->label('Maps API')
                    ->boolean(),

                Tables\Columns\TextColumn::make('batas_simpan_dokumen_hari')
                    ->label('Simpan Bukti')
                    ->suffix(' hari'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengaturanLayanans::route('/'),
            'create' => Pages\CreatePengaturanLayanan::route('/create'),
            'edit' => Pages\EditPengaturanLayanan::route('/{record}/edit'),
        ];
    }
}   