<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'total_jarak_km' => 'decimal:2',
        'biaya_jasa' => 'integer',
        'biaya_express' => 'integer',
        'total_biaya_jasa' => 'integer',
        'tanggal_order' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (blank($order->kode_order)) {
                $date = now()->format('Ymd');

                $countToday = self::query()
                    ->whereDate('created_at', now()->toDateString())
                    ->count() + 1;

                $order->kode_order = 'ESR-' . $date . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);
            }

            if (blank($order->tanggal_order)) {
                $order->tanggal_order = now();
            }

            if (blank($order->admin_id) && Auth::check()) {
                $order->admin_id = Auth::id();
            }

            $order->biaya_jasa = $order->biaya_jasa ?? 0;
            $order->biaya_express = $order->biaya_express ?? 0;
            $order->total_biaya_jasa = $order->total_biaya_jasa ?? 0;
        });
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function jenisLayanan(): BelongsTo
    {
        return $this->belongsTo(LayananJasaSuruh::class, 'jenis_layanan_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function kurir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kurir_id');
    }

    public function detailOrders(): HasMany
    {
        return $this->hasMany(DetailOrder::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function danaTitip(): HasOne
    {
        return $this->hasOne(DanaTitip::class);
    }

    public function penugasanKurir(): HasOne
    {
        return $this->hasOne(PenugasanKurir::class);
    }

    public function buktiSerahTerimas(): HasMany
    {
        return $this->hasMany(BuktiSerahTerima::class);
    }
}