<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PermintaanLayananResource\Pages;
use App\Models\KategoriLayanan;
use App\Models\PermintaanLayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class PermintaanLayananResource extends Resource
{
    protected static ?string $model = PermintaanLayanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Operasional';

    protected static ?string $navigationLabel = 'Permintaan Layanan';

    protected static ?string $modelLabel = 'Permintaan Layanan';

    protected static ?string $pluralModelLabel = 'Permintaan Layanan';

    protected static ?int $navigationSort = 0;

    protected static bool $shouldRegisterNavigation = true;

    public static function getNavigationBadge(): ?string
    {
        return (string) PermintaanLayanan::where('status', 'baru')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pemesan')
                    ->description('Informasi utama pemesan yang membuat request layanan.')
                    ->schema([
                        Forms\Components\TextInput::make('kode')
                            ->label('Kode Request')
                            ->default(fn () => 'REQ-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('nama_pemesan')
                            ->label('Nama Pemesan')
                            ->placeholder('Contoh: Muhammad Rizky')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('no_hp')
                            ->label('Nomor WhatsApp')
                            ->placeholder('Contoh: 081234567890')
                            ->tel()
                            ->required()
                            ->maxLength(30),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Detail Permintaan')
                    ->description('Data layanan yang diminta oleh pemesan.')
                    ->schema([
                        Forms\Components\Select::make('kategori_layanan_id')
                            ->label('Kategori Layanan')
                            ->relationship('kategoriLayanan', 'nama')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::setBiayaLayanan($get, $set);
                            }),

                        Forms\Components\Select::make('tipe_layanan')
                            ->label('Tipe Layanan')
                            ->options([
                                'normal' => 'Normal',
                                'express' => 'Express',
                            ])
                            ->default('normal')
                            ->native(false)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::setBiayaLayanan($get, $set);
                            }),

                        Forms\Components\TextInput::make('biaya_layanan')
                            ->label('Biaya Layanan')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Permintaan')
                            ->placeholder('Contoh: Titip beli ayam geprek')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Permintaan')
                            ->placeholder('Tulis detail permintaan dari pemesan.')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('lokasi_awal')
                            ->label('Lokasi Awal')
                            ->placeholder('Contoh: Kantin Kampus')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('lokasi_tujuan')
                            ->label('Lokasi Tujuan')
                            ->placeholder('Contoh: Gedung Fakultas')
                            ->maxLength(255),

                        Forms\Components\Select::make('status')
                            ->label('Status Permintaan')
                            ->options([
                                'baru' => 'Baru',
                                'diproses' => 'Diproses',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('baru')
                            ->native(false)
                            ->required(),

                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan Admin')
                            ->placeholder('Tambahkan catatan internal admin jika diperlukan.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Akses WhatsApp')
                    ->description('Link WhatsApp dibuat otomatis berdasarkan data permintaan.')
                    ->schema([
                        Forms\Components\Placeholder::make('whatsapp_url_preview')
                            ->label('Link WhatsApp')
                            ->content(function (?PermintaanLayanan $record) {
                                if (! $record || blank($record->whatsapp_url)) {
                                    return 'Link WhatsApp akan tersedia setelah data disimpan.';
                                }

                                return new HtmlString(
                                    '<a href="' . e($record->whatsapp_url) . '" target="_blank" style="color:#16a34a;font-weight:700;text-decoration:underline;">Buka Chat WhatsApp</a>'
                                );
                            }),
                    ])
                    ->hidden(fn (?PermintaanLayanan $record) => ! $record),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('nama_pemesan')
                    ->label('Pemesan')
                    ->searchable()
                    ->sortable()
                    ->description(fn (PermintaanLayanan $record): string => $record->no_hp ?? '-'),

                Tables\Columns\TextColumn::make('kategoriLayanan.nama')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->limit(28)
                    ->tooltip(fn (PermintaanLayanan $record): ?string => $record->judul),

                Tables\Columns\TextColumn::make('tipe_layanan')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'normal' => 'gray',
                        'express' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'normal' => 'Normal',
                        'express' => 'Express',
                        default => '-',
                    }),

                Tables\Columns\TextColumn::make('biaya_layanan')
                    ->label('Biaya')
                    ->formatStateUsing(fn ($state): string => 'Rp ' . number_format((float) $state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'baru' => 'info',
                        'diproses' => 'warning',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'baru' => 'Baru',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default => '-',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Request')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'baru' => 'Baru',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('tipe_layanan')
                    ->label('Filter Tipe Layanan')
                    ->options([
                        'normal' => 'Normal',
                        'express' => 'Express',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('kategori_layanan_id')
                    ->label('Filter Kategori')
                    ->relationship('kategoriLayanan', 'nama')
                    ->searchable()
                    ->preload()
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Detail'),

                    Tables\Actions\EditAction::make()
                        ->label('Edit'),

                    Tables\Actions\Action::make('buka_whatsapp')
                        ->label('Buka WhatsApp')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->url(fn (PermintaanLayanan $record): ?string => $record->whatsapp_url)
                        ->openUrlInNewTab()
                        ->visible(fn (PermintaanLayanan $record): bool => filled($record->whatsapp_url)),

                    Tables\Actions\Action::make('ubah_status_diproses')
                        ->label('Tandai Diproses')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->visible(fn (PermintaanLayanan $record): bool => $record->status !== 'diproses')
                        ->action(function (PermintaanLayanan $record) {
                            $record->update([
                                'status' => 'diproses',
                            ]);

                            Notification::make()
                                ->title('Status berhasil diubah menjadi Diproses')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('ubah_status_selesai')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (PermintaanLayanan $record): bool => $record->status !== 'selesai')
                        ->action(function (PermintaanLayanan $record) {
                            $record->update([
                                'status' => 'selesai',
                            ]);

                            Notification::make()
                                ->title('Status berhasil diubah menjadi Selesai')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('ubah_status_dibatalkan')
                        ->label('Tandai Dibatalkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (PermintaanLayanan $record): bool => $record->status !== 'dibatalkan')
                        ->action(function (PermintaanLayanan $record) {
                            $record->update([
                                'status' => 'dibatalkan',
                            ]);

                            Notification::make()
                                ->title('Status berhasil diubah menjadi Dibatalkan')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size('sm')
                    ->color('gray')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->emptyStateHeading('Belum ada permintaan layanan')
            ->emptyStateDescription('Data request dari pengguna akan muncul di halaman ini.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermintaanLayanans::route('/'),
            'create' => Pages\CreatePermintaanLayanan::route('/create'),
            'edit' => Pages\EditPermintaanLayanan::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    protected static function setBiayaLayanan(Forms\Get $get, Forms\Set $set): void
    {
        $kategoriId = $get('kategori_layanan_id');
        $tipeLayanan = $get('tipe_layanan') ?? 'normal';

        if (! $kategoriId) {
            $set('biaya_layanan', 0);
            return;
        }

        $kategori = KategoriLayanan::find($kategoriId);

        if (! $kategori) {
            $set('biaya_layanan', 0);
            return;
        }

        $biaya = $tipeLayanan === 'express'
            ? $kategori->biaya_express
            : $kategori->biaya_normal;

        $set('biaya_layanan', $biaya);
    }
}