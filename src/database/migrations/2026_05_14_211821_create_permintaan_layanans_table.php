<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_layanan', function (Blueprint $table) {
            $table->id();

            $table->string('kode')->unique();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('kategori_layanan_id')
                ->constrained('kategori_layanan')
                ->cascadeOnDelete();

            $table->string('nama_pemesan');
            $table->string('no_hp', 30);

            $table->string('judul');
            $table->text('deskripsi')->nullable();

            $table->string('lokasi_awal')->nullable();
            $table->string('lokasi_tujuan')->nullable();

            $table->enum('tipe_layanan', ['normal', 'express'])->default('normal');
            $table->decimal('biaya_layanan', 12, 2)->default(0);

            $table->enum('status', [
                'baru',
                'diproses',
                'selesai',
                'dibatalkan',
            ])->default('baru');

            $table->text('catatan_admin')->nullable();
            $table->text('whatsapp_url')->nullable();

            $table->timestamps();

            $table->index(['status', 'tipe_layanan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_layanan');
    }
};