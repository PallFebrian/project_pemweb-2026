<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komplain_pelanggans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->string('nama_pelanggan');
            $table->string('nomor_whatsapp', 20);

            $table->text('isi_komplain');

            $table->string('bukti_komplain')
                ->nullable();

            $table->enum('status_komplain', [
                'baru',
                'diproses',
                'selesai',
                'ditolak',
            ])->default('baru');

            $table->text('tanggapan_admin')
                ->nullable();

            $table->foreignId('ditangani_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('tanggal_komplain')
                ->nullable();

            $table->dateTime('tanggal_selesai')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komplain_pelanggans');
    }
};