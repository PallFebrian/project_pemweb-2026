<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermintaanLayanan extends Model
{
    protected $table = 'permintaan_layanan';

    protected $fillable = [
        'kode',
        'user_id',
        'kategori_layanan_id',
        'nama_pemesan',
        'no_hp',
        'judul',
        'deskripsi',
        'lokasi_awal',
        'lokasi_tujuan',
        'tipe_layanan',
        'biaya_layanan',
        'status',
        'catatan_admin',
        'whatsapp_url',
    ];

    protected $casts = [
        'biaya_layanan' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (PermintaanLayanan $permintaan) {
            $permintaan->whatsapp_url = $permintaan->generateWhatsappUrl();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategoriLayanan(): BelongsTo
    {
        return $this->belongsTo(KategoriLayanan::class, 'kategori_layanan_id');
    }

    public function logStatus(): HasMany
    {
        return $this->hasMany(LogStatusPermintaan::class, 'permintaan_layanan_id');
    }

    public function generateWhatsappUrl(): string
    {
        $nomorAdmin = config('services.whatsapp.admin_number', '6281234567890');

        $kategori = $this->kategoriLayanan?->nama ?? '-';

        $pesan = "Halo Admin, saya ingin membuat permintaan layanan.\n\n"
            . "Kode: {$this->kode}\n"
            . "Nama: {$this->nama_pemesan}\n"
            . "No HP: {$this->no_hp}\n"
            . "Kategori: {$kategori}\n"
            . "Tipe Layanan: {$this->tipe_layanan}\n"
            . "Judul: {$this->judul}\n"
            . "Deskripsi: {$this->deskripsi}\n"
            . "Lokasi Awal: {$this->lokasi_awal}\n"
            . "Lokasi Tujuan: {$this->lokasi_tujuan}\n"
            . "Biaya Layanan: Rp" . number_format((float) $this->biaya_layanan, 0, ',', '.') . "\n\n"
            . "Mohon diproses ya.";

        return 'https://wa.me/' . $nomorAdmin . '?text=' . urlencode($pesan);
    }
}