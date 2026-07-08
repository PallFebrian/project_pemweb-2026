<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class MonitoringPesanan extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPesanan = Order::query()->count();

        $pesananAktif = Order::query()
            ->whereIn('status_order', [
                'menunggu_verifikasi',
                'menunggu_dana_titip',
                'menunggu_kurir',
                'dalam_perjalanan',
            ])
            ->count();

        $pesananSelesai = Order::query()
            ->where('status_order', 'selesai')
            ->count();

        $pendapatanJasa = Order::query()
            ->where('status_order', 'selesai')
            ->sum('total_biaya_jasa');

        return [
            Stat::make(
                'Total Pesanan',
                number_format($totalPesanan, 0, ',', '.')
            )
                ->description('Seluruh pesanan yang tercatat')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make(
                'Pesanan Aktif',
                number_format($pesananAktif, 0, ',', '.')
            )
                ->description('Pesanan yang masih diproses')
                ->icon('heroicon-o-arrow-path')
                ->color('warning'),

            Stat::make(
                'Pesanan Selesai',
                number_format($pesananSelesai, 0, ',', '.')
            )
                ->description('Pesanan yang telah selesai')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make(
                'Pendapatan Jasa',
                'Rp' . number_format(
                    $pendapatanJasa,
                    0,
                    ',',
                    '.'
                )
            )
                ->description('Pendapatan dari pesanan selesai')
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
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
            'owner',
            'pemilik_bisnis',
        ]) || in_array($user->role, [
            'admin',
            'owner',
            'pemilik_bisnis',
        ], true);
    }
}