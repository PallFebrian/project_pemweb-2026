<?php

namespace App\Services;

use App\Models\PengaturanLayanan;

class PriceCalculator
{
    public function calculate(float $jarakKm, bool $isExpress = false): array
    {
        $setting = PengaturanLayanan::query()->first();

        $biayaFlatSatuKm = $setting?->biaya_flat_satu_km ?? 7000;
        $biayaPerKm = $setting?->biaya_per_km ?? 5000;
        $surchargeExpressPerDuaKm = $setting?->surcharge_express_per_dua_km ?? 10000;

        if ($jarakKm <= 1) {
            $biayaJasa = $biayaFlatSatuKm;
        } else {
            $biayaJasa = (int) ceil($jarakKm * $biayaPerKm);
        }

        $biayaExpress = $isExpress
            ? (int) ceil($jarakKm / 2) * $surchargeExpressPerDuaKm
            : 0;

        return [
            'biaya_jasa' => $biayaJasa,
            'biaya_express' => $biayaExpress,
            'total_biaya_jasa' => $biayaJasa + $biayaExpress,
        ];
    }
}