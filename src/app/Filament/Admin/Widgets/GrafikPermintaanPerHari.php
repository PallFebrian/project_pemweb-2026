<?php

namespace App\Filament\Admin\Widgets;

use App\Models\PermintaanLayanan;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class GrafikPermintaanPerHari extends ChartWidget
{
    protected static ?string $heading = 'Grafik Request 7 Hari Terakhir';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $tanggalAwal = now()->subDays(6)->startOfDay();

        $dataRequest = PermintaanLayanan::query()
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->where('created_at', '>=', $tanggalAwal)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i);
            $tanggalKey = $tanggal->toDateString();

            $labels[] = Carbon::parse($tanggalKey)->translatedFormat('d M');
            $data[] = (int) ($dataRequest[$tanggalKey] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Request',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}