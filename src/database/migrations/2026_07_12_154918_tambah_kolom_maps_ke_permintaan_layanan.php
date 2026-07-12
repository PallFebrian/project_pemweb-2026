<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('permintaan_layanan')) {
            return;
        }

        Schema::table('permintaan_layanan', function (Blueprint $table) {
            if (! Schema::hasColumn('permintaan_layanan', 'lokasi_awal_lat')) {
                $table->decimal('lokasi_awal_lat', 10, 7)->nullable()->after('lokasi_awal');
            }

            if (! Schema::hasColumn('permintaan_layanan', 'lokasi_awal_lng')) {
                $table->decimal('lokasi_awal_lng', 10, 7)->nullable()->after('lokasi_awal_lat');
            }

            if (! Schema::hasColumn('permintaan_layanan', 'lokasi_tujuan_lat')) {
                $table->decimal('lokasi_tujuan_lat', 10, 7)->nullable()->after('lokasi_tujuan');
            }

            if (! Schema::hasColumn('permintaan_layanan', 'lokasi_tujuan_lng')) {
                $table->decimal('lokasi_tujuan_lng', 10, 7)->nullable()->after('lokasi_tujuan_lat');
            }

            if (! Schema::hasColumn('permintaan_layanan', 'estimasi_jarak_km')) {
                $table->decimal('estimasi_jarak_km', 10, 2)->nullable()->after('lokasi_tujuan_lng');
            }

            if (! Schema::hasColumn('permintaan_layanan', 'biaya_perjalanan')) {
                $table->decimal('biaya_perjalanan', 12, 2)->default(0)->after('estimasi_jarak_km');
            }

            if (! Schema::hasColumn('permintaan_layanan', 'estimasi_total_biaya')) {
                $table->decimal('estimasi_total_biaya', 12, 2)->default(0)->after('biaya_perjalanan');
            }

            if (! Schema::hasColumn('permintaan_layanan', 'data_peta')) {
                $table->json('data_peta')->nullable()->after('estimasi_total_biaya');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('permintaan_layanan')) {
            return;
        }

        Schema::table('permintaan_layanan', function (Blueprint $table) {
            $columns = [
                'data_peta',
                'estimasi_total_biaya',
                'biaya_perjalanan',
                'estimasi_jarak_km',
                'lokasi_tujuan_lng',
                'lokasi_tujuan_lat',
                'lokasi_awal_lng',
                'lokasi_awal_lat',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('permintaan_layanan', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};