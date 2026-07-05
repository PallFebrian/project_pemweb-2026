<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LayananJasaSuruh extends Model
{
    protected $guarded = [];

    protected $casts = [
        'harga_dasar' => 'decimal:2',
        'bisa_express' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (LayananJasaSuruh $layanan) {
            if (blank($layanan->slug)) {
                $layanan->slug = Str::slug($layanan->nama_layanan);
            }
        });

        static::updating(function (LayananJasaSuruh $layanan) {
            if ($layanan->isDirty('nama_layanan') && blank($layanan->slug)) {
                $layanan->slug = Str::slug($layanan->nama_layanan);
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriLayanan::class, 'kategori_layanan_id');
    }
}