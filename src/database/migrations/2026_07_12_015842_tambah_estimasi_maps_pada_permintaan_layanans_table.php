<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function tableName(): ?string
    {
        if (Schema::hasTable('permintaan_layanan')) {
            return 'permintaan_layanan';
        }

        if (Schema::hasTable('permintaan_layanans')) {
            return 'permintaan_layanans';
        }

        return null;
    }

    public function up(): void
    {
        $tableName = $this->tableName();

        if (! $tableName) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (! Schema::hasColumn($tableName, 'lokasi_awal_lat')) {
                $table->decimal('lokasi_awal_lat', 10, 7)->nullable()->after('lokasi_awal');
            }

            if (! Schema::hasColumn($tableName, 'lokasi_awal_lng')) {
                $table->decimal('lokasi_awal_lng', 10, 7)->nullable()->after('lokasi_awal_lat');
            }

            if (! Schema::hasColumn($tableName, 'lokasi_tujuan_lat')) {
                $table->decimal('lokasi_tujuan_lat', 10, 7)->nullable()->after('lokasi_tujuan');
            }

            if (! Schema::hasColumn($tableName, 'lokasi_tujuan_lng')) {
                $table->decimal('lokasi_tujuan_lng', 10, 7)->nullable()->after('lokasi_tujuan_lat');
            }

            if (! Schema::hasColumn($tableName, 'estimasi_jarak_km')) {
                $table->decimal('estimasi_jarak_km', 10, 2)->nullable()->after('lokasi_tujuan_lng');
            }

            if (! Schema::hasColumn($tableName, 'biaya_perjalanan')) {
                $table->decimal('biaya_perjalanan', 12, 2)->default(0)->after('estimasi_jarak_km');
            }

            if (! Schema::hasColumn($tableName, 'estimasi_total_biaya')) {
                $table->decimal('estimasi_total_biaya', 12, 2)->default(0)->after('biaya_perjalanan');
            }

            if (! Schema::hasColumn($tableName, 'data_peta')) {
                $table->json('data_peta')->nullable()->after('estimasi_total_biaya');
            }
        });
    }

    public function down(): void
    {
        $tableName = $this->tableName();

        if (! $tableName) {
            return;
        }

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            $columns = [
                'lokasi_awal_lat',
                'lokasi_awal_lng',
                'lokasi_tujuan_lat',
                'lokasi_tujuan_lng',
                'estimasi_jarak_km',
                'biaya_perjalanan',
                'estimasi_total_biaya',
                'data_peta',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn($tableName, $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};