<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kategori_layanan')) {
            Schema::table('kategori_layanan', function (Blueprint $table) {
                if (! Schema::hasColumn('kategori_layanan', 'butuh_dana_pembelian')) {
                    $table
                        ->boolean('butuh_dana_pembelian')
                        ->default(false);
                }
            });

            DB::table('kategori_layanan')
                ->where('nama', 'like', '%beli%')
                ->orWhere('nama', 'like', '%belanja%')
                ->orWhere('nama', 'like', '%makanan%')
                ->orWhere('nama', 'like', '%minuman%')
                ->update([
                    'butuh_dana_pembelian' => true,
                ]);
        }

        if (Schema::hasTable('permintaan_layanan')) {
            Schema::table('permintaan_layanan', function (Blueprint $table) {
                if (! Schema::hasColumn('permintaan_layanan', 'dana_pembelian')) {
                    $table
                        ->decimal('dana_pembelian', 12, 2)
                        ->default(0);
                }

                if (! Schema::hasColumn('permintaan_layanan', 'catatan_dana_pembelian')) {
                    $table
                        ->text('catatan_dana_pembelian')
                        ->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('permintaan_layanan')) {
            Schema::table('permintaan_layanan', function (Blueprint $table) {
                if (Schema::hasColumn('permintaan_layanan', 'catatan_dana_pembelian')) {
                    $table->dropColumn('catatan_dana_pembelian');
                }

                if (Schema::hasColumn('permintaan_layanan', 'dana_pembelian')) {
                    $table->dropColumn('dana_pembelian');
                }
            });
        }

        if (Schema::hasTable('kategori_layanan')) {
            Schema::table('kategori_layanan', function (Blueprint $table) {
                if (Schema::hasColumn('kategori_layanan', 'butuh_dana_pembelian')) {
                    $table->dropColumn('butuh_dana_pembelian');
                }
            });
        }
    }
};