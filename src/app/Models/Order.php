<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

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
        static::creating(function (Order $order): void {
            if (blank($order->kode_order)) {
                $tanggal = now()->format('Ymd');

                $nomorUrut = self::query()
                    ->whereDate('created_at', now()->toDateString())
                    ->count() + 1;

                $order->kode_order = 'ESR-'
                    . $tanggal
                    . '-'
                    . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);
            }

            if (blank($order->tanggal_order)) {
                $order->tanggal_order = now();
            }

            if (blank($order->admin_id) && Auth::check()) {
                $order->admin_id = Auth::id();
            }

            $order->status_order ??= 'menunggu_verifikasi';
            $order->biaya_jasa ??= 0;
            $order->biaya_express ??= 0;
            $order->total_biaya_jasa ??= 0;
        });

        static::created(function (Order $order): void {
            $order->riwayatStatusOrders()->create([
                'status' => $order->status_order,
                'catatan' => 'Pesanan dibuat dengan status awal.',
                'diubah_oleh' => Auth::id(),
                'waktu_status' => now(),
            ]);
        });

        static::updated(function (Order $order): void {
            if (! $order->wasChanged('status_order')) {
                return;
            }

            $statusSebelumnya = $order->getOriginal('status_order');
            $statusSekarang = $order->status_order;

            $order->riwayatStatusOrders()->create([
                'status' => $statusSekarang,
                'catatan' => 'Status berubah dari '
                    . self::labelStatus($statusSebelumnya)
                    . ' menjadi '
                    . self::labelStatus($statusSekarang)
                    . '.',
                'diubah_oleh' => Auth::id(),
                'waktu_status' => now(),
            ]);
        });
    }

    public static function labelStatus(?string $status): string
    {
        return match ($status) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'menunggu_dana_titip' => 'Menunggu Dana Titip',
            'menunggu_kurir' => 'Menunggu Kurir',
            'dalam_perjalanan' => 'Dalam Perjalanan',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => $status ?? '-',
        };
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function jenisLayanan(): BelongsTo
    {
        return $this->belongsTo(
            LayananJasaSuruh::class,
            'jenis_layanan_id'
        );
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

    public function riwayatStatusOrders(): HasMany
    {
        return $this->hasMany(RiwayatStatusOrder::class);
    }

    public function komplainPelanggans(): HasMany
    {
    return $this->hasMany(KomplainPelanggan::class);
    }
}