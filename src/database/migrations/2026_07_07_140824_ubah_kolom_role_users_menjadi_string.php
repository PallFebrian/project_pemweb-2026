<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY role VARCHAR(50)
            NOT NULL DEFAULT 'user'
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE users
            SET role = 'user'
            WHERE role NOT IN ('admin', 'owner', 'user')
        ");

        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('admin', 'owner', 'user')
            NOT NULL DEFAULT 'user'
        ");
    }
};