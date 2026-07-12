<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('layanan_jasa_suruhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_layanan_id')
                ->constrained('kategori_layanan')
                ->cascadeOnDelete();

            $table->string('nama_layanan');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();

            $table->decimal('harga_dasar', 12, 2)->default(0);
            $table->string('satuan')->nullable();

            $table->boolean('bisa_express')->default(false);
            $table->boolean('status')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_jasa_suruhs');
    }
};
