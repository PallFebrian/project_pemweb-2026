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
        static::saving(function (DanaTitip $danaTitip) {
            $danaTitip->selisih_dana = $danaTitip->dana_diterima - $danaTitip->dana_terpakai;
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}