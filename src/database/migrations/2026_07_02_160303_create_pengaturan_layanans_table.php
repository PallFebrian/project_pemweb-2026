<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturan_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengaturan')->default('default');

            $table->text('titik_awal_basecamp');
            $table->decimal('latitude_basecamp', 10, 7)->nullable();
            $table->decimal('longitude_basecamp', 10, 7)->nullable();

            $table->integer('biaya_flat_satu_km')->default(7000);
            $table->integer('biaya_per_km')->default(5000);
            $table->integer('surcharge_express_per_dua_km')->default(10000);

            $table->boolean('google_maps_api_enabled')->default(true);
            $table->integer('batas_simpan_dokumen_hari')->default(30);

            $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_layanans');
    }
};
