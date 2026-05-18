<?php

namespace App\Filament\Admin\Widgets;

use App\Models\PermintaanLayanan;
use Filament\Widgets\ChartWidget;

class GrafikTipeLayanan extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Tipe Layanan';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        $normal = PermintaanLayanan::query()
            ->where('tipe_layanan', 'normal')
            ->count();

        $express = PermintaanLayanan::query()
            ->where('tipe_layanan', 'express')
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total Request',
                    'data' => [$normal, $express],
                ],
            ],
            'labels' => ['Normal', 'Express'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}