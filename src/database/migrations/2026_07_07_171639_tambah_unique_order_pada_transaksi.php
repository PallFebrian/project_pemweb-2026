<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $indexes = [
            'pembayarans' =>
                'pembayarans_order_id_unique',

            'dana_titips' =>
                'dana_titips_order_id_unique',

            'penugasan_kurirs' =>
                'penugasan_kurirs_order_id_unique',
        ];

        foreach ($indexes as $tableName => $indexName) {
            if ($this->indexExists($tableName, $indexName)) {
                continue;
            }

            Schema::table(
                $tableName,
                function (Blueprint $table) use ($indexName): void {
                    $table->unique(
                        'order_id',
                        $indexName
                    );
                }
            );
        }
    }

    public function down(): void
    {
        $indexes = [
            'penugasan_kurirs' =>
                'penugasan_kurirs_order_id_unique',

            'dana_titips' =>
                'dana_titips_order_id_unique',

            'pembayarans' =>
                'pembayarans_order_id_unique',
        ];

        foreach ($indexes as $tableName => $indexName) {
            if (! $this->indexExists($tableName, $indexName)) {
                continue;
            }

            $supportIndex =
                $tableName . '_order_id_support_index';

            if (! $this->indexExists(
                $tableName,
                $supportIndex
            )) {
                Schema::table(
                    $tableName,
                    function (Blueprint $table) use (
                        $supportIndex
                    ): void {
                        $table->index(
                            'order_id',
                            $supportIndex
                        );
                    }
                );
            }

            Schema::table(
                $tableName,
                function (Blueprint $table) use (
                    $indexName
                ): void {
                    $table->dropUnique($indexName);
                }
            );
        }
    }

    private function indexExists(
        string $tableName,
        string $indexName
    ): bool {
        return DB::table(
            'information_schema.statistics'
        )
            ->where(
                'table_schema',
                DB::getDatabaseName()
            )
            ->where('table_name', $tableName)
            ->where('index_name', $indexName)
            ->exists();
    }
};