<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProjectInitialize extends Command
{
    /**
     * Nama dan opsi command.
     */
    protected $signature = 'project:init
                            {--seed : Jalankan database seeder}
                            {--shield : Generate permission Filament Shield}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Inisialisasi project tanpa menghapus data database';

    /**
     * Jalankan command.
     */
    public function handle(): int
    {
        $this->info('Menjalankan inisialisasi project...');

        /*
         * Hanya menjalankan migration yang belum pernah dijalankan.
         * Data yang sudah ada tidak akan dihapus.
         */
        $this->call('migrate', [
            '--force' => true,
        ]);

        /*
         * Generate permission Shield hanya jika opsi --shield diberikan.
         */
        if ($this->option('shield')) {
            $this->info('Generate permission Filament Shield...');

            $this->call('shield:generate', [
                '--all' => true,
                '--panel' => 'admin',
            ]);
        }

        /*
         * Jalankan seeder hanya jika opsi --seed diberikan.
         */
        if ($this->option('seed')) {
            $this->info('Menjalankan database seeder...');

            $this->call('db:seed', [
                '--force' => true,
            ]);
        }

        /*
         * Membersihkan cache Filament dan Laravel.
         */
        $this->call('filament:optimize-clear');
        $this->call('optimize:clear');

        $this->newLine();
        $this->info('Inisialisasi selesai. Data database tetap aman.');

        return self::SUCCESS;
    }
}