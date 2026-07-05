<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PenugasanKurirResource\Pages;
use App\Models\Order;
use App\Models\PenugasanKurir;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PenugasanKurirResource extends Resource
{
    protected static ?string $model = PenugasanKurir::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Penugasan Kurir';

    protected static ?string $modelLabel = 'Penugasan Kurir';

    protected static ?string $pluralModelLabel = 'Penugasan Kurir';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Penugasan')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Kode Order')
                            ->options(fn () => Order::query()
                                ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
                                ->orderByDesc('created_at')
                                ->get()
                                ->mapWithKeys(fn (Order $order) => [
                                    $order->id => $order->kode_order . ' - ' . $order->nama_pelanggan,
                                ]))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('kurir_id')
                            ->label('Kurir')
                            ->options(fn () => User::query()
                                ->whereHas('roles', fn ($query) => $query->where('name', 'kurir'))
                                ->orderBy('name')
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status_penugasan')
                            ->label('Status Penugasan')
                            ->options([
                                'menunggu' => 'Menunggu',
                                'berjalan' => 'Berjalan',
                                'sampai_eksekusi' => 'Sampai di Eksekusi',
                                'sampai_tujuan' => 'Sampai di Tujuan',
                                'selesai' => 'Selesai',
                            ])
                            ->default('menunggu')
                            ->required(),

                        Forms\Components\Textarea::make('catatan_penugasan')
                            ->label('Catatan Penugasan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Waktu Proses Kurir')
                    ->schema([
                        Forms\Components\DateTimePicker::make('waktu_penugasan')
                            ->label('Waktu Penugasan')
                            ->seconds(false),

                        Forms\Components\DateTimePicker::make('waktu_berangkat')
                            ->label('Waktu Berangkat')
                            ->seconds(false),

                        Forms\Components\DateTimePicker::make('waktu_sampai_eksekusi')
                            ->label('Waktu Sampai Eksekusi')
                            ->seconds(false),

                        Forms\Components\DateTimePicker::make('waktu_sampai_tujuan')
                            ->label('Waktu Sampai Tujuan')
                            ->seconds(false),
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

                Tables\Columns\TextColumn::make('kurir.name')
                    ->label('Kurir')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_penugasan')
                    ->label('Status Penugasan')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu' => 'Menunggu',
                        'berjalan' => 'Berjalan',
                        'sampai_eksekusi' => 'Sampai di Eksekusi',
                        'sampai_tujuan' => 'Sampai di Tujuan',
                        'selesai' => 'Selesai',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'gray',
                        'berjalan' => 'info',
                        'sampai_eksekusi' => 'warning',
                        'sampai_tujuan' => 'primary',
                        'selesai' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('waktu_penugasan')
                    ->label('Waktu Penugasan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('waktu_berangkat')
                    ->label('Berangkat')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('waktu_sampai_eksekusi')
                    ->label('Sampai Eksekusi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('waktu_sampai_tujuan')
                    ->label('Sampai Tujuan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_penugasan')
                    ->label('Status Penugasan')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'berjalan' => 'Berjalan',
                        'sampai_eksekusi' => 'Sampai di Eksekusi',
                        'sampai_tujuan' => 'Sampai di Tujuan',
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
            'index' => Pages\ListPenugasanKurirs::route('/'),
            'create' => Pages\CreatePenugasanKurir::route('/create'),
            'edit' => Pages\EditPenugasanKurir::route('/{record}/edit'),
        ];
    }
}