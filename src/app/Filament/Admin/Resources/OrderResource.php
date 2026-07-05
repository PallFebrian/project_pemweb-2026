<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Models\LayananJasaSuruh;
use App\Models\Order;
use App\Models\Pelanggan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'Pesanan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pelanggan')
                    ->schema([
                        Forms\Components\Select::make('pelanggan_id')
                            ->label('Pelanggan Terdaftar')
                            ->options(fn () => Pelanggan::query()
                                ->orderBy('nama')
                                ->pluck('nama', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if (! $state) {
                                    return;
                                }

                                $pelanggan = Pelanggan::find($state);

                                if ($pelanggan) {
                                    $set('nama_pelanggan', $pelanggan->nama);
                                    $set('nomor_whatsapp', $pelanggan->nomor_whatsapp);
                                }
                            }),

                        Forms\Components\TextInput::make('nama_pelanggan')
                            ->label('Nama Pelanggan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nomor_whatsapp')
                            ->label('Nomor WhatsApp')
                            ->required()
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('Contoh: 08123456789'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Detail Pesanan dari WhatsApp')
                    ->schema([
                        Forms\Components\TextInput::make('kode_order')
                            ->label('Kode Order')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Otomatis dibuat sistem'),

                        Forms\Components\Select::make('jenis_layanan_id')
                            ->label('Jenis Layanan')
                            ->options(fn () => LayananJasaSuruh::query()
                                ->where('status', true)
                                ->orderBy('nama_layanan')
                                ->pluck('nama_layanan', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('pilihan_layanan')
                            ->label('Pilihan Layanan')
                            ->options([
                                'normal' => 'Normal',
                                'express' => 'Express',
                            ])
                            ->default('normal')
                            ->required(),

                        Forms\Components\Textarea::make('alamat_eksekusi')
                            ->label('Alamat Eksekusi')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('alamat_tujuan')
                            ->label('Alamat Tujuan')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('detail_barang')
                            ->label('Detail Barang / Catatan Pesanan')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Jarak dan Biaya')
                    ->schema([
                        Forms\Components\TextInput::make('total_jarak_km')
                            ->label('Total Jarak')
                            ->numeric()
                            ->suffix('KM')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Select::make('sumber_jarak')
                            ->label('Sumber Jarak')
                            ->options([
                                'api' => 'Google Maps API',
                                'manual' => 'Manual',
                            ])
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('status_api_maps')
                            ->label('Status Google Maps API')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('biaya_jasa')
                            ->label('Biaya Jasa')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('biaya_express')
                            ->label('Biaya Express')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('total_biaya_jasa')
                            ->label('Total Biaya Jasa')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Status dan Penugasan')
                    ->schema([
                        Forms\Components\Hidden::make('status_order')
                            ->default('menunggu_verifikasi'),

                Forms\Components\Placeholder::make('status_order_display')
                    ->label('Status Order')
                        ->content(function (?Order $record): string {
                            $status = $record?->status_order ?? 'menunggu_verifikasi';

                        return match ($status) {
                            'menunggu_verifikasi' => 'Menunggu Verifikasi',
                            'menunggu_dana_titip' => 'Menunggu Dana Titip',
                            'menunggu_kurir' => 'Menunggu Kurir',
                            'dalam_perjalanan' => 'Dalam Perjalanan',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                        default => $status,
                };
            }),

                Forms\Components\Placeholder::make('kurir_display')
                    ->label('Kurir')
                    ->content(
                    fn (?Order $record): string =>
                        $record?->kurir?->name ?? 'Belum ditugaskan'
                ),

                Forms\Components\DateTimePicker::make('tanggal_order')
                    ->label('Tanggal Order')
                    ->seconds(false)
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Otomatis saat pesanan dibuat'),
            ])
            ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_order')
                    ->label('Kode Order')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nomor_whatsapp')
                    ->label('WhatsApp')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jenisLayanan.nama_layanan')
                    ->label('Layanan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pilihan_layanan')
                    ->label('Pilihan')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'normal' => 'Normal',
                        'express' => 'Express',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'normal' => 'gray',
                        'express' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total_jarak_km')
                    ->label('Jarak')
                    ->suffix(' KM')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_biaya_jasa')
                    ->label('Total Biaya')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_order')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'menunggu_dana_titip' => 'Menunggu Dana Titip',
                        'menunggu_kurir' => 'Menunggu Kurir',
                        'dalam_perjalanan' => 'Dalam Perjalanan',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu_verifikasi' => 'gray',
                        'menunggu_dana_titip' => 'warning',
                        'menunggu_kurir' => 'info',
                        'dalam_perjalanan' => 'primary',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('kurir.name')
                    ->label('Kurir')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_order')
                    ->label('Status Order')
                    ->options([
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'menunggu_dana_titip' => 'Menunggu Dana Titip',
                        'menunggu_kurir' => 'Menunggu Kurir',
                        'dalam_perjalanan' => 'Dalam Perjalanan',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),

                Tables\Filters\SelectFilter::make('pilihan_layanan')
                    ->label('Pilihan Layanan')
                    ->options([
                        'normal' => 'Normal',
                        'express' => 'Express',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}