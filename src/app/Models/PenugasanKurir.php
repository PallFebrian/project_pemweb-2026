<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenugasanKurir extends Model
{
    protected $guarded = [];

    protected $casts = [
        'waktu_penugasan' => 'datetime',
        'waktu_berangkat' => 'datetime',
        'waktu_sampai_eksekusi' => 'datetime',
        'waktu_sampai_tujuan' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (PenugasanKurir $penugasanKurir) {
            if (blank($penugasanKurir->waktu_penugasan)) {
                $penugasanKurir->waktu_penugasan = now();
            }
        });

        static::saved(function (PenugasanKurir $penugasanKurir) {
            $penugasanKurir->order?->update([
                'kurir_id' => $penugasanKurir->kurir_id,
                'status_order' => match ($penugasanKurir->status_penugasan) {
                    'menunggu' => 'menunggu_kurir',
                    'berjalan', 'sampai_eksekusi', 'sampai_tujuan' => 'dalam_perjalanan',
                    'selesai' => 'selesai',
                    default => $penugasanKurir->order->status_order,
                },
            ]);
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function kurir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kurir_id');
    }
}