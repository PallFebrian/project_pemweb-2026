<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected function getTableName(): ?string
    {
        if (Schema::hasTable('permintaan_layanan')) {
            return 'permintaan_layanan';
        }

        if (Schema::hasTable('permintaan_layanans')) {
            return 'permintaan_layanans';
        }

        return null;
    }

    public function up(): void
    {
        $tableName = $this->getTableName();

        if (! $tableName) {
            return;
        }

        if (! Schema::hasColumn($tableName, 'dibaca_admin_pada')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->timestamp('dibaca_admin_pada')
                    ->nullable()
                    ->after('status');
            });
        }
    }

    public function down(): void
    {
        $tableName = $this->getTableName();

        if (! $tableName) {
            return;
        }

        if (Schema::hasColumn($tableName, 'dibaca_admin_pada')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('dibaca_admin_pada');
            });
        }
    }
};