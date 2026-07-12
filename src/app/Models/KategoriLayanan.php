<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class KategoriLayanan extends Model
{
    protected $table = 'kategori_layanan';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'biaya_normal',
        'biaya_express',
        'butuh_dana_pembelian',
        'estimasi_normal',
        'estimasi_express',
        'aktif',
    ];

    protected $casts = [
        'biaya_normal' => 'decimal:2',
        'biaya_express' => 'decimal:2',
        'aktif' => 'boolean',
        'butuh_dana_pembelian' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (KategoriLayanan $kategori) {
            if (blank($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });

        static::updating(function (KategoriLayanan $kategori) {
            if ($kategori->isDirty('nama') && blank($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });
    }

    public function permintaanLayanan(): HasMany
    {
        return $this->hasMany(PermintaanLayanan::class, 'kategori_layanan_id');
    }

    public function layananJasaSuruhs(): HasMany
    {
        return $this->hasMany(LayananJasaSuruh::class, 'kategori_layanan_id');
    }
}