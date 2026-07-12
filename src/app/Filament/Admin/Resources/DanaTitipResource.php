<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DanaTitipResource\Pages;
use App\Models\DanaTitip;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DanaTitipResource extends Resource
{
    protected static ?string $model = DanaTitip::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Dana Titip';

    protected static ?string $modelLabel = 'Dana Titip';

    protected static ?string $pluralModelLabel = 'Dana Titip';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pesanan')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Pesanan')
                            ->options(function (?DanaTitip $record): array {
                                return Order::query()
                                    ->where(function (Builder $query) use ($record): void {
                                        $query->whereDoesntHave('danaTitip');

                                        if ($record?->order_id) {
                                            $query->orWhere('orders.id', $record->order_id);
                                        }
                                    })
                                    ->latest()
                                    ->get()
                                    ->mapWithKeys(
                                        fn (Order $order): array => [
                                            $order->id =>
                                                $order->kode_order
                                                . ' - '
                                                . $order->nama_pelanggan,
                                        ]
                                    )
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabledOn('edit'),
                    ]),

                Forms\Components\Section::make('Informasi Dana Titip Barang')
                    ->schema([
                        Forms\Components\TextInput::make('estimasi_dana_titip')
                            ->label('Estimasi Dana Titip')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->required(),

                        Forms\Components\TextInput::make('dana_diterima')
                            ->label('Dana Diterima')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->required()
                            ->live(),

                        Forms\Components\TextInput::make('dana_terpakai')
                            ->label('Dana Terpakai')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->required()
                            ->live(),

                        Forms\Components\TextInput::make('selisih_dana')
                            ->label('Selisih Dana')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Select::make('status_dana_titip')
                            ->label('Status Dana Titip')
                            ->options([
                                'belum_diterima' => 'Belum Diterima',
                                'diterima' => 'Diterima',
                                'selesai' => 'Selesai',
                            ])
                            ->default('belum_diterima')
                            ->required(),

                        Forms\Components\FileUpload::make('bukti_transfer')
                            ->label('Bukti Transfer Dana Titip')
                            ->image()
                            ->disk('public')
                            ->directory('bukti-dana-titip')
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

                Tables\Columns\TextColumn::make('estimasi_dana_titip')
                    ->label('Estimasi')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dana_diterima')
                    ->label('Diterima')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dana_terpakai')
                    ->label('Terpakai')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('selisih_dana')
                    ->label('Selisih')
                    ->money('IDR')
                    ->sortable()
                    ->color(fn ($state): string => match (true) {
                        $state > 0 => 'success',
                        $state < 0 => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status_dana_titip')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'belum_diterima' => 'Belum Diterima',
                        'diterima' => 'Diterima',
                        'selesai' => 'Selesai',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'belum_diterima' => 'gray',
                        'diterima' => 'warning',
                        'selesai' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\ImageColumn::make('bukti_transfer')
                    ->label('Bukti'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_dana_titip')
                    ->label('Status Dana Titip')
                    ->options([
                        'belum_diterima' => 'Belum Diterima',
                        'diterima' => 'Diterima',
                        'selesai' => 'Selesai',
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
            'index' => Pages\ListDanaTitips::route('/'),
            'create' => Pages\CreateDanaTitip::route('/create'),
            'edit' => Pages\EditDanaTitip::route('/{record}/edit'),
        ];
    }
}