<?php

use App\Services\PriceCalculator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function (): void {
    Schema::dropIfExists('pengaturan_layanans');

    Schema::create('pengaturan_layanans', function (Blueprint $table): void {
        $table->id();

        $table->unsignedBigInteger('biaya_flat_satu_km')
            ->default(7000);

        $table->unsignedBigInteger('biaya_per_km')
            ->default(5000);

        $table->unsignedBigInteger(
            'surcharge_express_per_dua_km'
        )->default(10000);
    });

    DB::table('pengaturan_layanans')->insert([
        'biaya_flat_satu_km' => 7000,
        'biaya_per_km' => 5000,
        'surcharge_express_per_dua_km' => 10000,
    ]);
});

test('biaya normal satu kilometer memakai tarif flat', function () {
    $hasil = app(PriceCalculator::class)->calculate(
        jarakKm: 1,
        isExpress: false
    );

    expect($hasil)->toBe([
        'biaya_jasa' => 7000,
        'biaya_express' => 0,
        'total_biaya_jasa' => 7000,
    ]);
});

test('biaya normal empat kilometer dihitung per kilometer', function () {
    $hasil = app(PriceCalculator::class)->calculate(
        jarakKm: 4,
        isExpress: false
    );

    expect($hasil)->toBe([
        'biaya_jasa' => 20000,
        'biaya_express' => 0,
        'total_biaya_jasa' => 20000,
    ]);
});

test('biaya express empat kilometer mendapat surcharge', function () {
    $hasil = app(PriceCalculator::class)->calculate(
        jarakKm: 4,
        isExpress: true
    );

    expect($hasil)->toBe([
        'biaya_jasa' => 20000,
        'biaya_express' => 20000,
        'total_biaya_jasa' => 40000,
    ]);
});

test('jarak pecahan dibulatkan sesuai rumus tarif', function () {
    $hasil = app(PriceCalculator::class)->calculate(
        jarakKm: 4.1,
        isExpress: true
    );

    expect($hasil)->toBe([
        'biaya_jasa' => 20500,
        'biaya_express' => 30000,
        'total_biaya_jasa' => 50500,
    ]);
});