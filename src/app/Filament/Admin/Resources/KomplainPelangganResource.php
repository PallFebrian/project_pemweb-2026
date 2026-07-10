<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KomplainPelangganResource\Pages;
use App\Models\KomplainPelanggan;
use App\Models\Order;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KomplainPelangganResource extends Resource
{
    protected static ?string $model = KomplainPelanggan::class;

    protected static ?string $navigationIcon =
        'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel =
        'Komplain Pelanggan';

    protected static ?string $modelLabel =
        'Komplain Pelanggan';

    protected static ?string $pluralModelLabel =
        'Komplain Pelanggan';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(
                    'Data Pesanan dan Pelanggan'
                )
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Pesanan')
                            ->options(
                                fn () => Order::query()
                                    ->latest()
                                    ->get()
                                    ->mapWithKeys(fn (Order $order) => [
                                        $order->id =>
                                            $order->kode_order
                                            . ' - '
                                            . $order->nama_pelanggan,
                                    ])
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->afterStateUpdated(
                                function (
                                    $state,
                                    Forms\Set $set
                                ): void {
                                    $order = Order::find($state);

                                    if (! $order) {
                                        return;
                                    }

                                    $set(
                                        'nama_pelanggan',
                                        $order->nama_pelanggan
                                    );

                                    $set(
                                        'nomor_whatsapp',
                                        $order->nomor_whatsapp
                                    );
                                }
                            ),

                        Forms\Components\TextInput::make(
                            'nama_pelanggan'
                        )
                            ->label('Nama Pelanggan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make(
                            'nomor_whatsapp'
                        )
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])
                    ->columns(3),

                Forms\Components\Section::make(
                    'Detail Komplain'
                )
                    ->schema([
                        Forms\Components\Textarea::make(
                            'isi_komplain'
                        )
                            ->label('Isi Komplain')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make(
                            'bukti_komplain'
                        )
                            ->label('Bukti Komplain')
                            ->directory('komplain-pelanggan')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'application/pdf',
                            ])
                            ->maxSize(1024)
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make(
                            'tanggal_komplain'
                        )
                            ->label('Tanggal Komplain')
                            ->seconds(false)
                            ->default(now())
                            ->required(),
                    ]),

                Forms\Components\Section::make(
                    'Penanganan Komplain'
                )
                    ->schema([
                        Forms\Components\Select::make(
                            'status_komplain'
                        )
                            ->label('Status Komplain')
                            ->options([
                                'baru' => 'Baru',
                                'diproses' => 'Diproses',
                                'selesai' => 'Selesai',
                                'ditolak' => 'Ditolak',
                            ])
                            ->default('baru')
                            ->required(),

                        Forms\Components\Select::make(
                            'ditangani_oleh'
                        )
                            ->label('Ditangani Oleh')
                            ->options(
                                fn () => User::query()
                                    ->whereHas(
                                        'roles',
                                        fn (Builder $query) =>
                                            $query->whereIn('name', [
                                                'super_admin',
                                                'admin',
                                                'pemilik_bisnis',
                                            ])
                                    )
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->default(fn (): ?int => Auth::id()),

                        Forms\Components\DateTimePicker::make(
                            'tanggal_selesai'
                        )
                            ->label('Tanggal Selesai')
                            ->seconds(false)
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Textarea::make(
                            'tanggapan_admin'
                        )
                            ->label('Tanggapan Admin')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make(
                    'order.kode_order'
                )
                    ->label('Kode Order')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make(
                    'nama_pelanggan'
                )
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make(
                    'nomor_whatsapp'
                )
                    ->label('WhatsApp')
                    ->searchable(),

                Tables\Columns\TextColumn::make(
                    'isi_komplain'
                )
                    ->label('Komplain')
                    ->limit(40)
                    ->wrap(),

                Tables\Columns\TextColumn::make(
                    'status_komplain'
                )
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string =>
                            match ($state) {
                                'baru' => 'Baru',
                                'diproses' => 'Diproses',
                                'selesai' => 'Selesai',
                                'ditolak' => 'Ditolak',
                                default => $state,
                            }
                    )
                    ->color(
                        fn (string $state): string =>
                            match ($state) {
                                'baru' => 'warning',
                                'diproses' => 'info',
                                'selesai' => 'success',
                                'ditolak' => 'danger',
                                default => 'gray',
                            }
                    ),

                Tables\Columns\TextColumn::make(
                    'adminPenangan.name'
                )
                    ->label('Ditangani Oleh')
                    ->placeholder('Belum ditangani'),

                Tables\Columns\TextColumn::make(
                    'tanggal_komplain'
                )
                    ->label('Tanggal Komplain')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make(
                    'status_komplain'
                )
                    ->label('Status Komplain')
                    ->options([
                        'baru' => 'Baru',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'ditolak' => 'Ditolak',
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
            ->defaultSort('tanggal_komplain', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' =>
                Pages\ListKomplainPelanggans::route('/'),

            'create' =>
                Pages\CreateKomplainPelanggan::route('/create'),

            'edit' =>
                Pages\EditKomplainPelanggan::route(
                    '/{record}/edit'
                ),
        ];
    }
}