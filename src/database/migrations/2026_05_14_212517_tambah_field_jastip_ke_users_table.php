<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_hp', 30)->nullable()->after('email');
            $table->string('nim', 50)->nullable()->after('no_hp');
            $table->text('alamat')->nullable()->after('nim');
            $table->enum('role', ['admin', 'user', 'owner'])->default('user')->after('password');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'no_hp',
                'nim',
                'alamat',
                'role',
                'status',
            ]);
        });
    }
};