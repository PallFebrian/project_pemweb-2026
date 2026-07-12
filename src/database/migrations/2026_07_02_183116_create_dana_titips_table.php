<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dana_titips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->integer('estimasi_dana_titip')->default(0);
            $table->integer('dana_diterima')->default(0);
            $table->integer('dana_terpakai')->default(0);
            $table->integer('selisih_dana')->default(0);

            $table->enum('status_dana_titip', [
                'belum_diterima',
                'diterima',
                'selesai',
            ])->default('belum_diterima');

            $table->string('bukti_transfer')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dana_titips');
    }
};