<?php

namespace App\Filament\Admin\Pages;

use App\Models\Order;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class LaporanPesanan extends Page
{
    protected static ?string $navigationIcon =
        'heroicon-o-document-chart-bar';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan Pesanan';

    protected static ?string $title = 'Laporan Pesanan';

    protected static ?int $navigationSort = 1;

    protected static string $view =
        'filament.admin.pages.laporan-pesanan';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'tanggal_mulai' => now()
                ->startOfMonth()
                ->format('Y-m-d'),

            'tanggal_selesai' => now()
                ->endOfMonth()
                ->format('Y-m-d'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live(),

                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->afterOrEqual('tanggal_mulai')
                    ->live(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function getQueryLaporan(): Builder
    {
        $tanggalMulai = $this->data['tanggal_mulai']
            ?? now()->startOfMonth()->format('Y-m-d');

        $tanggalSelesai = $this->data['tanggal_selesai']
            ?? now()->endOfMonth()->format('Y-m-d');

        return Order::query()
            ->with([
                'jenisLayanan',
                'kurir',
            ])
            ->whereDate('tanggal_order', '>=', $tanggalMulai)
            ->whereDate('tanggal_order', '<=', $tanggalSelesai);
    }

    public function getDataLaporan(): array
    {
        $query = $this->getQueryLaporan();

        return [
            'total_pesanan' => (clone $query)->count(),

            'pesanan_aktif' => (clone $query)
                ->whereIn('status_order', [
                    'menunggu_verifikasi',
                    'menunggu_dana_titip',
                    'menunggu_kurir',
                    'dalam_perjalanan',
                ])
                ->count(),

            'pesanan_selesai' => (clone $query)
                ->where('status_order', 'selesai')
                ->count(),

            'pesanan_dibatalkan' => (clone $query)
                ->where('status_order', 'dibatalkan')
                ->count(),

            'pendapatan_jasa' => (clone $query)
                ->where('status_order', 'selesai')
                ->sum('total_biaya_jasa'),

            'pesanan' => (clone $query)
                ->latest('tanggal_order')
                ->get(),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->hasAnyRole([
            'super_admin',
            'admin',
            'owner',
            'pemilik_bisnis',
        ]) || in_array($user->role, [
            'admin',
            'owner',
            'pemilik_bisnis',
        ], true);
    }
}