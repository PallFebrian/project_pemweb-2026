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
        static::creating(function (
            PenugasanKurir $penugasanKurir
        ): void {
            $penugasanKurir->waktu_penugasan ??= now();
            $penugasanKurir->status_penugasan ??= 'menunggu';
        });

        static::saving(function (
            PenugasanKurir $penugasanKurir
        ): void {
            match ($penugasanKurir->status_penugasan) {
                'berjalan' => self::setWaktuBerangkat(
                    $penugasanKurir
                ),

                'sampai_eksekusi' => self::setWaktuEksekusi(
                    $penugasanKurir
                ),

                'sampai_tujuan',
                'selesai' => self::setWaktuTujuan(
                    $penugasanKurir
                ),

                default => null,
            };
        });

        static::saved(function (
            PenugasanKurir $penugasanKurir
        ): void {
            $order = $penugasanKurir->order;

            if (! $order) {
                return;
            }

            if ((int) $order->kurir_id !== (int) $penugasanKurir->kurir_id) {
                $order->kurir_id = $penugasanKurir->kurir_id;
            }

            /*
             * Order yang sudah selesai atau dibatalkan
             * tidak boleh dikembalikan ke status sebelumnya.
             */
            if (in_array($order->status_order, [
                'selesai',
                'dibatalkan',
            ], true)) {
                if ($order->isDirty()) {
                    $order->save();
                }

                return;
            }

            $statusOrderBaru = match (
                $penugasanKurir->status_penugasan
            ) {
                'menunggu' => 'menunggu_kurir',

                'berjalan',
                'sampai_eksekusi',
                'sampai_tujuan' => 'dalam_perjalanan',

                /*
                * Status penugasan selesai tidak langsung
                * mengubah status order. Penyelesaian order
                * dilakukan lewat rekonsiliasi admin.
                */
                'selesai' => $order->status_order,

                default => $order->status_order,
            };

            if ($order->status_order !== $statusOrderBaru) {
                $order->status_order = $statusOrderBaru;
            }

            if ($order->isDirty()) {
                $order->save();
            }
        });
    }

    private static function setWaktuBerangkat(
        PenugasanKurir $penugasanKurir
    ): void {
        $penugasanKurir->waktu_berangkat ??= now();
    }

    private static function setWaktuEksekusi(
        PenugasanKurir $penugasanKurir
    ): void {
        $penugasanKurir->waktu_berangkat ??= now();
        $penugasanKurir->waktu_sampai_eksekusi ??= now();
    }

    private static function setWaktuTujuan(
        PenugasanKurir $penugasanKurir
    ): void {
        $penugasanKurir->waktu_berangkat ??= now();
        $penugasanKurir->waktu_sampai_eksekusi ??= now();
        $penugasanKurir->waktu_sampai_tujuan ??= now();
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