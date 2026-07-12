<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LayananJasaSuruhResource\Pages;
use App\Models\LayananJasaSuruh;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LayananJasaSuruhResource extends Resource
{
    protected static ?string $model = LayananJasaSuruh::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Layanan Jasa Suruh';

    protected static ?string $modelLabel = 'Layanan Jasa Suruh';

    protected static ?string $pluralModelLabel = 'Layanan Jasa Suruh';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Layanan')
                    ->schema([
                        Forms\Components\Select::make('kategori_layanan_id')
                            ->label('Kategori Layanan')
                            ->relationship(
                                name: 'kategori',
                                titleAttribute: 'nama',
                                modifyQueryUsing: fn ($query) => $query->where('aktif', true)
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('nama_layanan')
                            ->label('Nama Layanan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pengaturan Layanan')
                    ->schema([
                        Forms\Components\TextInput::make('harga_dasar')
                            ->label('Harga Dasar')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('satuan')
                            ->label('Satuan')
                            ->placeholder('Contoh: per pesanan')
                            ->maxLength(255),

                        Forms\Components\Toggle::make('bisa_express')
                            ->label('Bisa Express')
                            ->default(false),

                        Forms\Components\Toggle::make('butuh_dana_titip')
                            ->label('Butuh Dana Titip')
                            ->helperText(
                                'Aktifkan jika kurir perlu uang pelanggan untuk membeli barang.'
                            )
                            ->default(false),

                        Forms\Components\Toggle::make('status')
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
                Tables\Columns\TextColumn::make('nama_layanan')
                    ->label('Nama Layanan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori.nama')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_dasar')
                    ->label('Harga Dasar')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->placeholder('-'),

                Tables\Columns\IconColumn::make('bisa_express')
                    ->label('Express')
                    ->boolean(),

                Tables\Columns\IconColumn::make('butuh_dana_titip')
                    ->label('Dana Titip')
                    ->boolean(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),

                Tables\Filters\TernaryFilter::make('bisa_express')
                    ->label('Bisa Express')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
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
            'index' => Pages\ListLayananJasaSuruhs::route('/'),
            'create' => Pages\CreateLayananJasaSuruh::route('/create'),
            'edit' => Pages\EditLayananJasaSuruh::route('/{record}/edit'),
        ];
    }
}