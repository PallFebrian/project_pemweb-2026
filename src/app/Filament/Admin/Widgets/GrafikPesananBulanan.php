<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class GrafikPesananBulanan extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pesanan 6 Bulan Terakhir';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [];
        $totalPesanan = [];
        $pesananSelesai = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);

            $awalBulan = $bulan->copy()->startOfMonth();
            $akhirBulan = $bulan->copy()->endOfMonth();

            $labels[] = $bulan->translatedFormat('M Y');

            $totalPesanan[] = Order::query()
                ->whereBetween('created_at', [
                    $awalBulan,
                    $akhirBulan,
                ])
                ->count();

            $pesananSelesai[] = Order::query()
                ->where('status_order', 'selesai')
                ->whereBetween('created_at', [
                    $awalBulan,
                    $akhirBulan,
                ])
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pesanan',
                    'data' => $totalPesanan,
                ],
                [
                    'label' => 'Pesanan Selesai',
                    'data' => $pesananSelesai,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->hasAnyRole([
            'super_admin',
            'admin',
            'pemilik_bisnis',
        ]) || in_array($user->role, [
            'admin',
            'pemilik_bisnis',
        ], true);
    }
}