<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DanaTitip extends Model
{
    protected $guarded = [];

    protected $casts = [
        'estimasi_dana_titip' => 'integer',
        'dana_diterima' => 'integer',
        'dana_terpakai' => 'integer',
        'selisih_dana' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (DanaTitip $danaTitip): void {
            $danaDiterima = (int) ($danaTitip->dana_diterima ?? 0);
            $danaTerpakai = (int) ($danaTitip->dana_terpakai ?? 0);

            $danaTitip->selisih_dana =
                $danaDiterima - $danaTerpakai;
        });

        static::saved(function (DanaTitip $danaTitip): void {
            $order = $danaTitip->order;

            if (! $order) {
                return;
            }

            if (in_array($order->status_order, [
                'selesai',
                'dibatalkan',
                'dalam_perjalanan',
            ], true)) {
                return;
            }

            if ($danaTitip->status_dana_titip === 'diterima') {
                if (in_array($order->status_order, [
                    'menunggu_verifikasi',
                    'menunggu_dana_titip',
                ], true)) {
                    $order->update([
                        'status_order' => 'menunggu_kurir',
                    ]);
                }

                return;
            }

            if (
                $danaTitip->status_dana_titip !== 'selesai'
                && $order->status_order === 'menunggu_verifikasi'
            ) {
                $order->update([
                    'status_order' => 'menunggu_dana_titip',
                ]);
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}