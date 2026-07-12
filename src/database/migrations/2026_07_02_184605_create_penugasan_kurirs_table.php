<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penugasan_kurirs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('kurir_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->dateTime('waktu_penugasan')->nullable();
            $table->dateTime('waktu_berangkat')->nullable();
            $table->dateTime('waktu_sampai_eksekusi')->nullable();
            $table->dateTime('waktu_sampai_tujuan')->nullable();

            $table->enum('status_penugasan', [
                'menunggu',
                'berjalan',
                'sampai_eksekusi',
                'sampai_tujuan',
                'selesai',
            ])->default('menunggu');

            $table->text('catatan_penugasan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasan_kurirs');
    }
};