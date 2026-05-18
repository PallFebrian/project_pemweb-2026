<?php

namespace App\Filament\Admin\Widgets;

use App\Models\KategoriLayanan;
use App\Models\PermintaanLayanan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikPermintaanOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRequest = PermintaanLayanan::query()->count();

        $requestBaru = PermintaanLayanan::query()
            ->where('status', 'baru')
            ->count();

        $requestDiproses = PermintaanLayanan::query()
            ->where('status', 'diproses')
            ->count();

        $requestSelesai = PermintaanLayanan::query()
            ->where('status', 'selesai')
            ->count();

        $requestNormal = PermintaanLayanan::query()
            ->where('tipe_layanan', 'normal')
            ->count();

        $requestExpress = PermintaanLayanan::query()
            ->where('tipe_layanan', 'express')
            ->count();

        $totalKategori = KategoriLayanan::query()->count();

        $estimasiPendapatan = PermintaanLayanan::query()
            ->whereIn('status', ['diproses', 'selesai'])
            ->sum('biaya_layanan');

        return [
            Stat::make('Total Request', $totalRequest)
                ->description('Semua permintaan layanan')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('Request Baru', $requestBaru)
                ->description('Menunggu diproses admin')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color('info'),

            Stat::make('Diproses', $requestDiproses)
                ->description('Sedang dikerjakan')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('Selesai', $requestSelesai)
                ->description('Permintaan selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Normal', $requestNormal)
                ->description('Request tipe normal')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray'),

            Stat::make('Express', $requestExpress)
                ->description('Request tipe express')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('warning'),

            Stat::make('Kategori Layanan', $totalKategori)
                ->description('Total kategori tersedia')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('primary'),

            Stat::make('Estimasi Pendapatan', 'Rp ' . number_format((float) $estimasiPendapatan, 0, ',', '.'))
                ->description('Dari request diproses & selesai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}