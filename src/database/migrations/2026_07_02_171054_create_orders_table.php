<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('kode_order')->unique();

            $table->foreignId('pelanggan_id')
                ->nullable()
                ->constrained('pelanggans')
                ->nullOnDelete();

            $table->string('nama_pelanggan');
            $table->string('nomor_whatsapp');

            $table->foreignId('jenis_layanan_id')
                ->constrained('layanan_jasa_suruhs')
                ->restrictOnDelete();

            $table->text('alamat_eksekusi');
            $table->text('alamat_tujuan');
            $table->text('detail_barang')->nullable();

            $table->enum('pilihan_layanan', [
                'normal',
                'express',
            ])->default('normal');

            $table->decimal('total_jarak_km', 8, 2)->nullable();

            $table->enum('sumber_jarak', [
                'api',
                'manual',
            ])->nullable();

            $table->string('status_api_maps')->nullable();

            $table->integer('biaya_jasa')->default(0);
            $table->integer('biaya_express')->default(0);
            $table->integer('total_biaya_jasa')->default(0);

            $table->enum('status_order', [
                'menunggu_verifikasi',
                'menunggu_dana_titip',
                'menunggu_kurir',
                'dalam_perjalanan',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_verifikasi');

            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('kurir_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('tanggal_order')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};