<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->enum('metode_pembayaran', [
                'full_transfer',
                'cod',
            ])->default('cod');

            $table->string('channel_pembayaran')->nullable();

            $table->integer('jumlah_bayar')->default(0);

            $table->string('bukti_pembayaran')->nullable();

            $table->enum('status_pembayaran', [
                'pending',
                'lunas',
                'cod',
            ])->default('pending');

            $table->dateTime('tanggal_bayar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};