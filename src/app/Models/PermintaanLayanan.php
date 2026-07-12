<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class PermintaanLayanan extends Model
{
    protected $table = 'permintaan_layanan';

    protected $fillable = [
        'kode',
        'user_id',
        'kategori_layanan_id',
        'nama_pemesan',
        'no_hp',
        'judul',
        'deskripsi',
        'lokasi_awal',
        'lokasi_tujuan',
        'tipe_layanan',
        'biaya_layanan',
        'dana_pembelian',
        'catatan_dana_pembelian',

        'lokasi_awal_lat',
        'lokasi_awal_lng',
        'lokasi_tujuan_lat',
        'lokasi_tujuan_lng',
        'estimasi_jarak_km',
        'biaya_perjalanan',
        'estimasi_total_biaya',
        'data_peta',

        'status',
        'catatan_admin',
        'whatsapp_url',
        'dibaca_admin_pada',
    ];

    protected $casts = [
        'biaya_layanan' => 'decimal:2',
        'dana_pembelian' => 'decimal:2',
        'biaya_perjalanan' => 'decimal:2',
        'estimasi_total_biaya' => 'decimal:2',
        'estimasi_jarak_km' => 'decimal:2',
        'lokasi_awal_lat' => 'decimal:7',
        'lokasi_awal_lng' => 'decimal:7',
        'lokasi_tujuan_lat' => 'decimal:7',
        'lokasi_tujuan_lng' => 'decimal:7',
        'data_peta' => 'array',
        'dibaca_admin_pada' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saving(function (PermintaanLayanan $permintaan) {
            $permintaan->whatsapp_url = $permintaan->generateWhatsappUrl();
        });

        static::updated(function (PermintaanLayanan $permintaan) {
            if (! $permintaan->wasChanged('status')) {
                return;
            }

            LogStatusPermintaan::create([
                'permintaan_layanan_id' => $permintaan->id,
                'user_id' => Auth::id(),
                'status_lama' => $permintaan->getOriginal('status'),
                'status_baru' => $permintaan->status,
                'catatan' => $permintaan->catatan_admin ?: 'Status permintaan diperbarui oleh admin.',
            ]);
        });
    }

    public function getWhatsappUrlAttribute($value): string
    {
        return $this->generateWhatsappUrl();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategoriLayanan(): BelongsTo
    {
        return $this->belongsTo(KategoriLayanan::class, 'kategori_layanan_id');
    }

    public function logStatusPermintaan(): HasMany
    {
        return $this->hasMany(LogStatusPermintaan::class, 'permintaan_layanan_id');
    }

    public function generateWhatsappUrl(): string
    {
        $nomorAdmin = $this->formatNomorWhatsapp(
            config('services.whatsapp.admin_number', '6281385184263')
        );

        $kategori = $this->kategoriLayanan?->nama ?? '-';

        $dataPeta = $this->ambilDataPeta();

        $lokasiAwalLat =
            $this->lokasi_awal_lat
            ?? data_get($dataPeta, 'lokasi_awal.lat');

        $lokasiAwalLng =
            $this->lokasi_awal_lng
            ?? data_get($dataPeta, 'lokasi_awal.lng');

        $lokasiTujuanLat =
            $this->lokasi_tujuan_lat
            ?? data_get($dataPeta, 'lokasi_tujuan.lat');

        $lokasiTujuanLng =
            $this->lokasi_tujuan_lng
            ?? data_get($dataPeta, 'lokasi_tujuan.lng');

        $estimasiJarakKm =
            $this->estimasi_jarak_km
            ?? data_get($dataPeta, 'estimasi_jarak_km')
            ?? null;

        $biayaPerjalanan =
            $this->biaya_perjalanan
            ?? data_get($dataPeta, 'biaya_perjalanan')
            ?? $this->biaya_layanan
            ?? 0;

        $danaPembelian =
            $this->dana_pembelian
            ?? data_get($dataPeta, 'dana_pembelian')
            ?? 0;

        $catatanDanaPembelian =
            $this->catatan_dana_pembelian
            ?? data_get($dataPeta, 'catatan_dana_pembelian')
            ?? null;

        $totalYangPerluDisiapkan =
            $this->estimasi_total_biaya
            ?? data_get($dataPeta, 'estimasi_total_biaya')
            ?? ((float) $biayaPerjalanan + (float) $danaPembelian);

        $deskripsiBersih = trim(
            \Illuminate\Support\Str::before(
                (string) $this->deskripsi,
                '--- Estimasi Rute & Biaya ---'
            )
        );

        $linkLokasiAwal = $this->googleMapsPointLink(
            $lokasiAwalLat,
            $lokasiAwalLng
        );

        $linkLokasiTujuan = $this->googleMapsPointLink(
            $lokasiTujuanLat,
            $lokasiTujuanLng
        );

        $linkRuteLengkap = $this->googleMapsRouteLink(
            $lokasiAwalLat,
            $lokasiAwalLng,
            $lokasiTujuanLat,
            $lokasiTujuanLng
        );

        $pesan = [
            'Halo Admin, saya ingin membuat permintaan layanan.',
            '',
            'Kode: ' . ($this->kode ?: '-'),
            'Nama: ' . ($this->nama_pemesan ?: '-'),
            'No HP: ' . ($this->no_hp ?: '-'),
            'Kategori: ' . $kategori,
            'Tipe Layanan: ' . ($this->tipe_layanan ?: '-'),
            'Judul: ' . ($this->judul ?: '-'),
            'Deskripsi: ' . ($deskripsiBersih ?: '-'),
            'Lokasi Awal: ' . ($this->lokasi_awal ?: '-'),
            'Lokasi Tujuan: ' . ($this->lokasi_tujuan ?: '-'),
            '',
            '--- Estimasi Rute & Biaya ---',
            'Rute: Basecamp ESA Runner → Lokasi Awal/Eksekusi → Lokasi Tujuan',
            'Estimasi jarak: ' . $this->formatJarak($estimasiJarakKm),
            'Biaya perjalanan: ' . $this->formatRupiah($biayaPerjalanan),
        ];

        if ((float) $danaPembelian > 0) {
            $pesan[] = 'Dana pembelian: ' . $this->formatRupiah($danaPembelian);

            if (filled($catatanDanaPembelian)) {
                $pesan[] = 'Catatan dana pembelian: ' . $catatanDanaPembelian;
            }
        }

        $pesan[] = 'Total yang perlu disiapkan: ' . $this->formatRupiah($totalYangPerluDisiapkan);
        $pesan[] = 'Catatan: estimasi masih dapat disesuaikan setelah verifikasi admin.';

        if ($linkRuteLengkap) {
            $pesan[] = '';
            $pesan[] = 'Link Rute Lengkap: ' . $linkRuteLengkap;
        }

        if ($linkLokasiAwal) {
            $pesan[] = 'Link Maps Lokasi Awal: ' . $linkLokasiAwal;
        }

        if ($linkLokasiTujuan) {
            $pesan[] = 'Link Maps Lokasi Tujuan: ' . $linkLokasiTujuan;
        }

        $pesan[] = '';
        $pesan[] = 'Mohon diproses ya, terima kasih.';

        return 'https://api.whatsapp.com/send/?phone=' .
            $nomorAdmin .
            '&text=' .
            urlencode(implode(PHP_EOL, $pesan));
    }

    protected function ambilDataPeta(): array
    {
        $dataPeta = $this->data_peta ?? [];

        if (is_string($dataPeta)) {
            return json_decode($dataPeta, true) ?: [];
        }

        if (is_array($dataPeta)) {
            return $dataPeta;
        }

        return [];
    }

    protected function googleMapsPointLink($lat, $lng): ?string
    {
        if (! is_numeric($lat) || ! is_numeric($lng)) {
            return null;
        }

        return 'https://www.google.com/maps?q=' .
            (float) $lat .
            ',' .
            (float) $lng;
    }

    protected function googleMapsRouteLink($awalLat, $awalLng, $tujuanLat, $tujuanLng): ?string
    {
        if (
            ! is_numeric($awalLat) ||
            ! is_numeric($awalLng) ||
            ! is_numeric($tujuanLat) ||
            ! is_numeric($tujuanLng)
        ) {
            return null;
        }

        $origin = '-6.2728365,106.5265246';

        $waypoint =
            (float) $awalLat .
            ',' .
            (float) $awalLng;

        $destination =
            (float) $tujuanLat .
            ',' .
            (float) $tujuanLng;

        return 'https://www.google.com/maps/dir/?api=1' .
            '&origin=' . $origin .
            '&waypoints=' . $waypoint .
            '&destination=' . $destination .
            '&travelmode=driving';
    }

    protected function formatRupiah($nilai): string
    {
        return 'Rp ' . number_format((float) $nilai, 0, ',', '.');
    }

    protected function formatJarak($nilai): string
    {
        if (! is_numeric($nilai) || (float) $nilai <= 0) {
            return '-';
        }

        return number_format((float) $nilai, 2, ',', '.') . ' km';
    }

    protected function formatNomorWhatsapp($nomor): string
    {
        $nomor = preg_replace('/[^0-9]/', '', (string) $nomor);

        if (str_starts_with($nomor, '0')) {
            return '62' . substr($nomor, 1);
        }

        if (str_starts_with($nomor, '8')) {
            return '62' . $nomor;
        }

        return $nomor;
    }
}