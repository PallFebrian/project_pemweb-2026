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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PenugasanKurirResource extends Resource
{
    protected static ?string $model = PenugasanKurir::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Operasional';

    protected static ?string $modelLabel = 'Penugasan Kurir';

    protected static ?string $pluralModelLabel = 'Penugasan Kurir';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return self::isKurir()
            ? 'Tugas Saya'
            : 'Penugasan Kurir';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Penugasan')
                    ->schema([
                    Forms\Components\Select::make('order_id')
                        ->label('Kode Order')
                        ->options(function (?PenugasanKurir $record): array {
                            return Order::query()
                                ->where('status_order', 'menunggu_kurir')
                                ->where(function (Builder $query) use ($record): void {
                                    $query->whereDoesntHave('penugasanKurir');

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
                        ->disabled(
                            fn (): bool =>
                                self::isKurir()
                                || self::isPemilikBisnis()
                        )
                        ->disabledOn('edit'),

                        Forms\Components\Select::make('kurir_id')
                            ->label('Kurir')
                            ->options(
                                fn () => User::query()
                                    ->whereHas(
                                        'roles',
                                        fn (Builder $query): Builder =>
                                            $query->where(
                                                'name',
                                                'kurir'
                                            )
                                    )
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(
                                fn (): bool =>
                                    self::isKurir()
                                    || self::isPemilikBisnis()
                            ),

                        Forms\Components\Select::make(
                            'status_penugasan'
                        )
                            ->label('Status Penugasan')
                            ->options([
                                'menunggu' => 'Menunggu',
                                'berjalan' => 'Berjalan',
                                'sampai_eksekusi' =>
                                    'Sampai di Eksekusi',
                                'sampai_tujuan' =>
                                    'Sampai di Tujuan',
                                'selesai' => 'Selesai',
                            ])
                            ->default('menunggu')
                            ->required()
                            ->disabled(
                                fn (): bool =>
                                    self::isKurir()
                                    || self::isPemilikBisnis()
                            ),

                        Forms\Components\Textarea::make(
                            'catatan_penugasan'
                        )
                            ->label('Catatan Penugasan')
                            ->rows(3)
                            ->columnSpanFull()
                            ->disabled(
                                fn (): bool =>
                                    self::isKurir()
                                    || self::isPemilikBisnis()
                            ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(
                    'Waktu Proses Kurir'
                )
                    ->schema([
                        Forms\Components\DateTimePicker::make(
                            'waktu_penugasan'
                        )
                            ->label('Waktu Penugasan')
                            ->seconds(false)
                            ->disabled(),

                        Forms\Components\DateTimePicker::make(
                            'waktu_berangkat'
                        )
                            ->label('Waktu Berangkat')
                            ->seconds(false)
                            ->disabled(),

                        Forms\Components\DateTimePicker::make(
                            'waktu_sampai_eksekusi'
                        )
                            ->label('Waktu Sampai Eksekusi')
                            ->seconds(false)
                            ->disabled(),

                        Forms\Components\DateTimePicker::make(
                            'waktu_sampai_tujuan'
                        )
                            ->label('Waktu Sampai Tujuan')
                            ->seconds(false)
                            ->disabled(),
                    ])
                    ->columns(2),
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
                    'order.nama_pelanggan'
                )
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make(
                    'order.jenisLayanan.nama_layanan'
                )
                    ->label('Layanan')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('kurir.name')
                    ->label('Kurir')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make(
                    'status_penugasan'
                )
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string =>
                            match ($state) {
                                'menunggu' => 'Menunggu',
                                'berjalan' => 'Berjalan',
                                'sampai_eksekusi' =>
                                    'Sampai di Eksekusi',
                                'sampai_tujuan' =>
                                    'Sampai di Tujuan',
                                'selesai' => 'Selesai',
                                default => $state,
                            }
                    )
                    ->color(
                        fn (string $state): string =>
                            match ($state) {
                                'menunggu' => 'gray',
                                'berjalan' => 'info',
                                'sampai_eksekusi' => 'warning',
                                'sampai_tujuan' => 'primary',
                                'selesai' => 'success',
                                default => 'gray',
                            }
                    ),

                Tables\Columns\TextColumn::make(
                    'waktu_penugasan'
                )
                    ->label('Ditugaskan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make(
                    'waktu_berangkat'
                )
                    ->label('Berangkat')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make(
                    'waktu_sampai_eksekusi'
                )
                    ->label('Sampai Eksekusi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make(
                    'waktu_sampai_tujuan'
                )
                    ->label('Sampai Tujuan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make(
                    'status_penugasan'
                )
                    ->label('Status Penugasan')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'berjalan' => 'Berjalan',
                        'sampai_eksekusi' =>
                            'Sampai di Eksekusi',
                        'sampai_tujuan' =>
                            'Sampai di Tujuan',
                        'selesai' => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->visible(
                        fn (): bool =>
                            self::isPemilikBisnis()
                    ),

                Tables\Actions\EditAction::make()
                    ->label(
                        self::isKurir()
                            ? 'Proses Tugas'
                            : 'Edit'
                    )
                    ->visible(
                        fn (): bool =>
                            self::isAdmin()
                            || self::isKurir()
                    ),

                Tables\Actions\DeleteAction::make()
                    ->visible(
                        fn (): bool => self::isAdmin()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
                    ->visible(
                        fn (): bool => self::isAdmin()
                    ),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with([
                'order.jenisLayanan',
                'kurir',
            ]);

        if (self::isKurir()) {
            $query->where(
                'kurir_id',
                Auth::id()
            );
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        return self::isAdmin()
            || self::isKurir()
            || self::isPemilikBisnis();
    }

    public static function canView(Model $record): bool
    {
        if (
            self::isAdmin()
            || self::isPemilikBisnis()
        ) {
            return true;
        }

        return self::isKurir()
            && (int) $record->kurir_id
                === (int) Auth::id();
    }

    public static function canCreate(): bool
    {
        return self::isAdmin();
    }

    public static function canEdit(Model $record): bool
    {
        if (self::isAdmin()) {
            return true;
        }

        return self::isKurir()
            && (int) $record->kurir_id
                === (int) Auth::id();
    }

    public static function canDelete(Model $record): bool
    {
        return self::isAdmin();
    }

    public static function canDeleteAny(): bool
    {
        return self::isAdmin();
    }

    protected static function isAdmin(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->hasAnyRole([
            'super_admin',
            'admin',
        ]) || in_array(
            $user->role,
            [
                'super_admin',
                'admin',
            ],
            true
        );
    }

    protected static function isKurir(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->hasRole('kurir')
            || $user->role === 'kurir';
    }

    protected static function isPemilikBisnis(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->hasAnyRole([
            'pemilik_bisnis',
        ]) || in_array(
            $user->role,
            [
                'pemilik_bisnis',
            ],
            true
        );
    }

    public static function getPages(): array
    {
        return [
            'index' =>
                Pages\ListPenugasanKurirs::route('/'),

            'create' =>
                Pages\CreatePenugasanKurir::route('/create'),

            'edit' =>
                Pages\EditPenugasanKurir::route(
                    '/{record}/edit'
                ),
        ];
    }
}