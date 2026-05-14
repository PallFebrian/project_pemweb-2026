<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KategoriLayananResource\Pages;
use App\Models\KategoriLayanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class KategoriLayananResource extends Resource
{
    protected static ?string $model = KategoriLayanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Kategori Layanan';

    protected static ?string $modelLabel = 'Kategori Layanan';

    protected static ?string $pluralModelLabel = 'Kategori Layanan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kategori')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Forms\Set $set) {
                                if ($operation === 'create' && filled($state)) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Biaya dan Estimasi')
                    ->schema([
                        Forms\Components\TextInput::make('biaya_normal')
                            ->label('Biaya Normal')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('biaya_express')
                            ->label('Biaya Express')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('estimasi_normal')
                            ->label('Estimasi Normal')
                            ->placeholder('Contoh: 30 - 60 menit'),

                        Forms\Components\TextInput::make('estimasi_express')
                            ->label('Estimasi Express')
                            ->placeholder('Contoh: 15 - 30 menit'),

                        Forms\Components\Toggle::make('aktif')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('biaya_normal')
                    ->label('Biaya Normal')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('biaya_express')
                    ->label('Biaya Express')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('estimasi_normal')
                    ->label('Estimasi Normal'),

                Tables\Columns\TextColumn::make('estimasi_express')
                    ->label('Estimasi Express'),

                Tables\Columns\IconColumn::make('aktif')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('aktif')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoriLayanans::route('/'),
            'create' => Pages\CreateKategoriLayanan::route('/create'),
            'edit' => Pages\EditKategoriLayanan::route('/{record}/edit'),
        ];
    }
}