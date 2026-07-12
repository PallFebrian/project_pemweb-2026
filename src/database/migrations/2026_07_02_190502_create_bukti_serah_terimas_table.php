<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukti_serah_terimas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('kurir_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('foto_barang')->nullable();
            $table->string('foto_serah_terima')->nullable();

            $table->text('catatan_bukti')->nullable();

            $table->dateTime('waktu_upload')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukti_serah_terimas');
    }
};