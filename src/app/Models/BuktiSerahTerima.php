<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiSerahTerima extends Model
{
    protected $guarded = [];

    protected $casts = [
        'waktu_upload' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (BuktiSerahTerima $buktiSerahTerima) {
            if (blank($buktiSerahTerima->waktu_upload)) {
                $buktiSerahTerima->waktu_upload = now();
            }
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