<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BuktiSerahTerimaResource\Pages;
use App\Models\BuktiSerahTerima;
use App\Models\Order;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BuktiSerahTerimaResource extends Resource
{
    protected static ?string $model = BuktiSerahTerima::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Bukti Serah Terima';

    protected static ?string $modelLabel = 'Bukti Serah Terima';

    protected static ?string $pluralModelLabel = 'Bukti Serah Terima';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pesanan')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Kode Order')
                            ->options(function () {
                                return Order::query()
                                    ->latest()
                                    ->pluck('kode_order', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (?int $state, Set $set): void {
                                if (! $state) {
                                    return;
                                }

                                $order = Order::query()->find($state);

                                if ($order?->kurir_id) {
                                    $set('kurir_id', $order->kurir_id);
                                }
                            }),

                        Forms\Components\Select::make('kurir_id')
                            ->label('Kurir')
                            ->options(function () {
                                return User::query()
                                    ->whereHas('roles', function ($query) {
                                        $query->where('name', 'kurir');
                                    })
                                    ->orderBy('name')
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DateTimePicker::make('waktu_upload')
                            ->label('Waktu Upload')
                            ->seconds(false),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Upload Bukti')
                    ->schema([
                        Forms\Components\FileUpload::make('foto_barang')
                            ->label('Foto Barang')
                            ->image()
                            ->disk('public')
                            ->maxSize(1024)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->directory(function (Get $get): string {
                                $order = Order::query()->find($get('order_id'));

                                return 'bukti-serah-terima/' . ($order?->kode_order ?? 'tanpa-order') . '/foto-barang';
                            })
                            ->visibility('public')
                            ->downloadable()
                            ->openable()
                            ->imagePreviewHeight('200')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('foto_serah_terima')
                            ->label('Foto Serah Terima')
                            ->image()
                            ->disk('public')
                            ->maxSize(1024)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->directory(function (Get $get): string {
                                $order = Order::query()->find($get('order_id'));

                                return 'bukti-serah-terima/' . ($order?->kode_order ?? 'tanpa-order') . '/foto-serah-terima';
                            })
                            ->visibility('public')
                            ->downloadable()
                            ->openable()
                            ->imagePreviewHeight('200')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('catatan_bukti')
                            ->label('Catatan Bukti')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
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

                Tables\Columns\TextColumn::make('kurir.name')
                    ->label('Kurir')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ImageColumn::make('foto_barang')
                    ->label('Foto Barang')
                    ->square(),

                Tables\Columns\ImageColumn::make('foto_serah_terima')
                    ->label('Foto Serah Terima')
                    ->square(),

                Tables\Columns\TextColumn::make('waktu_upload')
                    ->label('Waktu Upload')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListBuktiSerahTerimas::route('/'),
            'create' => Pages\CreateBuktiSerahTerima::route('/create'),
            'edit' => Pages\EditBuktiSerahTerima::route('/{record}/edit'),
        ];
    }
}