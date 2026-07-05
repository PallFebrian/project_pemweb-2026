<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PembayaranResource\Pages;
use App\Models\Order;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Pembayaran';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pesanan')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Kode Order')
                            ->options(fn () => Order::query()
                                ->orderByDesc('created_at')
                                ->get()
                                ->mapWithKeys(fn (Order $order) => [
                                    $order->id => $order->kode_order . ' - ' . $order->nama_pelanggan,
                                ]))
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Forms\Components\Section::make('Informasi Pembayaran Biaya Jasa')
                    ->schema([
                        Forms\Components\Select::make('metode_pembayaran')
                            ->label('Metode Pembayaran')
                            ->options([
                                'full_transfer' => 'Full Transfer di Awal',
                                'cod' => 'COD / Bayar Tunai di Akhir',
                            ])
                            ->default('cod')
                            ->required()
                            ->live(),

                        Forms\Components\TextInput::make('channel_pembayaran')
                            ->label('Channel Pembayaran')
                            ->placeholder('Contoh: BCA, DANA, OVO, Tunai')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('jumlah_bayar')
                            ->label('Jumlah Bayar')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->required(),

                        Forms\Components\Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending',
                                'lunas' => 'Lunas',
                                'cod' => 'COD / Bayar Akhir',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\DateTimePicker::make('tanggal_bayar')
                            ->label('Tanggal Bayar')
                            ->seconds(false),

                        Forms\Components\FileUpload::make('bukti_pembayaran')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->disk('public')
                            ->directory('bukti-pembayaran')
                            ->visibility('public')
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.kode_order')
                    ->label('Kode Order')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order.nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'full_transfer' => 'Full Transfer',
                        'cod' => 'COD',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'full_transfer' => 'success',
                        'cod' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('channel_pembayaran')
                    ->label('Channel')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('jumlah_bayar')
                    ->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'lunas' => 'Lunas',
                        'cod' => 'COD',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'lunas' => 'success',
                        'cod' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tanggal_bayar')
                    ->label('Tanggal Bayar')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('bukti_pembayaran')
                    ->label('Bukti'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('metode_pembayaran')
                    ->label('Metode Pembayaran')
                    ->options([
                        'full_transfer' => 'Full Transfer di Awal',
                        'cod' => 'COD / Bayar Akhir',
                    ]),

                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'lunas' => 'Lunas',
                        'cod' => 'COD',
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}