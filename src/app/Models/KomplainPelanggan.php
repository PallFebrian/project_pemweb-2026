<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomplainPelanggan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tanggal_komplain' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (KomplainPelanggan $komplain): void {
            $komplain->tanggal_komplain ??= now();
            $komplain->status_komplain ??= 'baru';
        });

        static::saving(function (KomplainPelanggan $komplain): void {
            if (
                $komplain->status_komplain === 'selesai'
                && blank($komplain->tanggal_selesai)
            ) {
                $komplain->tanggal_selesai = now();
            }

            if ($komplain->status_komplain !== 'selesai') {
                $komplain->tanggal_selesai = null;
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function adminPenangan(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'ditangani_oleh'
        );
    }
}