<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStatusOrder extends Model
{
    protected $guarded = [];

    protected $casts = [
        'waktu_status' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (RiwayatStatusOrder $riwayat) {
            if (blank($riwayat->waktu_status)) {
                $riwayat->waktu_status = now();
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function pengubah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}