<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogStatusPermintaan extends Model
{
    protected $table = 'log_status_permintaan';

    protected $fillable = [
        'permintaan_layanan_id',
        'user_id',
        'status_lama',
        'status_baru',
        'catatan',
    ];

    public function permintaanLayanan(): BelongsTo
    {
        return $this->belongsTo(PermintaanLayanan::class, 'permintaan_layanan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}