<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanan_jasa_suruhs', function (Blueprint $table) {
            $table->boolean('butuh_dana_titip')
                ->default(false)
                ->after('bisa_express');
        });
    }

    public function down(): void
    {
        Schema::table('layanan_jasa_suruhs', function (Blueprint $table) {
            $table->dropColumn('butuh_dana_titip');
        });
    }
};