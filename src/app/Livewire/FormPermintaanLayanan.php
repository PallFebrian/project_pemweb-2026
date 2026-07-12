<?php

namespace App\Livewire;

use App\Filament\Admin\Resources\PermintaanLayananResource;
use App\Models\KategoriLayanan;
use App\Models\LogStatusPermintaan;
use App\Models\PermintaanLayanan;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class FormPermintaanLayanan extends Component
{
    public $kategori_layanan_id = '';
    public $nama_pemesan = '';
    public $no_hp = '';
    public $judul = '';
    public $deskripsi = '';
    public $lokasi_awal = '';
    public $lokasi_tujuan = '';
    public $tipe_layanan = 'normal';

    public $lokasi_awal_lat = null;
    public $lokasi_awal_lng = null;
    public $lokasi_tujuan_lat = null;
    public $lokasi_tujuan_lng = null;

    public $estimasi_jarak_km = 0;
    public $biaya_dasar_layanan = 0;
    public $biaya_perjalanan = 0;
    public $estimasi_total_biaya = 0;
    public $biaya_layanan = 0;

    public bool $butuh_dana_pembelian = false;
    public $dana_pembelian = 0;
    public $catatan_dana_pembelian = '';

    public $tarif_km_pertama = 7000;
    public $tarif_km_berikutnya = 5000;

    public $pesan_sukses = '';

    public string $kode_berhasil = '';
    public string $no_hp_berhasil = '';
    public string $whatsapp_url_berhasil = '';
    public string $cek_status_url_berhasil = '';

    public function mount(): void
    {
        $this->hitungBiayaLayanan();
    }

    public function updatedKategoriLayananId(): void
    {
        $this->sinkronkanDanaPembelianDenganKategori();
        $this->hitungBiayaLayanan();
    }

    public function updatedTipeLayanan(): void
    {
        $this->hitungBiayaLayanan();
    }

    public function updatedEstimasiJarakKm(): void
    {
        $this->hitungBiayaPerjalanan();
    }

    public function updatedDanaPembelian(): void
    {
        $this->dana_pembelian = $this->normalisasiNominal($this->dana_pembelian);

        $this->hitungEstimasiTotal();
    }

    public function hitungBiayaLayanan(): void
    {
        $kategori = KategoriLayanan::find($this->kategori_layanan_id);

        if (! $kategori) {
            $this->biaya_dasar_layanan = 0;
            $this->hitungEstimasiTotal();

            return;
        }

        $this->biaya_dasar_layanan = $this->tipe_layanan === 'express'
            ? (float) $kategori->biaya_express
            : (float) $kategori->biaya_normal;

        $this->hitungEstimasiTotal();
    }

    public function hitungBiayaPerjalanan(): void
    {
        $jarakKm = max(
            0,
            (float) str_replace(',', '.', (string) $this->estimasi_jarak_km)
        );

        $this->estimasi_jarak_km = round($jarakKm, 2);

        if ($jarakKm <= 0) {
            $this->biaya_perjalanan = 0;
            $this->hitungEstimasiTotal();

            return;
        }

        /*
        * Rumus:
        * 1,54 KM => dihitung 1 KM => Rp7.000
        * 2,00 KM => dihitung 2 KM => Rp7.000 + Rp5.000
        * 3,20 KM => dihitung 3 KM => Rp7.000 + Rp10.000
        */
        $jarakDitagihkanKm = max(1, (int) floor($jarakKm));
        $kmBerikutnya = max(0, $jarakDitagihkanKm - 1);

        $this->biaya_perjalanan =
            (float) $this->tarif_km_pertama +
            ($kmBerikutnya * (float) $this->tarif_km_berikutnya);

        $this->hitungEstimasiTotal();
    }

    protected function sinkronkanDanaPembelianDenganKategori(): void
    {
        $kategori = KategoriLayanan::find($this->kategori_layanan_id);

        $this->butuh_dana_pembelian = $this->kategoriButuhDanaPembelian($kategori);

        if (! $this->butuh_dana_pembelian) {
            $this->dana_pembelian = 0;
            $this->catatan_dana_pembelian = '';
        }

        $this->hitungEstimasiTotal();
    }

    protected function kategoriButuhDanaPembelian(?KategoriLayanan $kategori): bool
    {
        if (! $kategori) {
            return false;
        }

        if (
            Schema::hasColumn($kategori->getTable(), 'butuh_dana_pembelian') &&
            (bool) $kategori->butuh_dana_pembelian
        ) {
            return true;
        }

        $namaKategori = Str::lower((string) $kategori->nama);

        return Str::contains($namaKategori, [
            'beli',
            'belanja',
            'makanan',
            'minuman',
        ]);
    }

    protected function normalisasiNominal($nilai): float
    {
        $angka = preg_replace('/[^0-9]/', '', (string) $nilai);

        return max(0, (float) $angka);
    }

    protected function hitungEstimasiTotal(): void
    {
        $danaPembelian = $this->butuh_dana_pembelian
            ? $this->normalisasiNominal($this->dana_pembelian)
            : 0;

        /*
        * Biaya layanan = biaya perjalanan saja.
        * Total yang perlu disiapkan = biaya perjalanan + dana pembelian.
        */
        $this->biaya_layanan = (float) $this->biaya_perjalanan;

        $this->estimasi_total_biaya =
            (float) $this->biaya_perjalanan +
            (float) $danaPembelian;
    }

    public function simpan(): void
    {
        $this->pesan_sukses = '';

        $tabelKategoriLayanan = (new KategoriLayanan())->getTable();

        $this->validate([
            'kategori_layanan_id' => [
                'required',
                Rule::exists($tabelKategoriLayanan, 'id'),
            ],
            'dana_pembelian' => [
                $this->butuh_dana_pembelian ? 'required' : 'nullable',
                'numeric',
                $this->butuh_dana_pembelian ? 'min:1' : 'min:0',
            ],
            'catatan_dana_pembelian' => ['nullable', 'string', 'max:1000'],
            'nama_pemesan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'lokasi_awal' => ['required', 'string', 'max:500'],
            'lokasi_tujuan' => ['required', 'string', 'max:500'],
            'lokasi_awal_lat' => ['nullable', 'numeric'],
            'lokasi_awal_lng' => ['nullable', 'numeric'],
            'lokasi_tujuan_lat' => ['nullable', 'numeric'],
            'lokasi_tujuan_lng' => ['nullable', 'numeric'],
            'estimasi_jarak_km' => ['nullable', 'numeric', 'min:0'],
            'tipe_layanan' => ['required', 'in:normal,express'],
        ],
        [
            'dana_pembelian.required' => 'Estimasi dana pembelian wajib diisi untuk layanan ini.',
            'dana_pembelian.numeric' => 'Dana pembelian harus berupa angka.',
            'dana_pembelian.min' => 'Dana pembelian harus lebih dari Rp0.',
        ]);

        
        $this->sinkronkanDanaPembelianDenganKategori();
        
        $this->dana_pembelian = $this->butuh_dana_pembelian
        ? $this->normalisasiNominal($this->dana_pembelian)
        : 0;
        
        if (! $this->butuh_dana_pembelian) {
            $this->catatan_dana_pembelian = '';
        }
            
        $this->hitungBiayaLayanan();
        $this->hitungBiayaPerjalanan();
        $this->hitungEstimasiTotal();

        $permintaan = PermintaanLayanan::create([
            'kode' => 'REQ-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
            'user_id' => Auth::id(),
            'kategori_layanan_id' => $this->kategori_layanan_id,
            'nama_pemesan' => $this->nama_pemesan,
            'no_hp' => $this->no_hp,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'lokasi_awal' => $this->lokasi_awal,
            'lokasi_tujuan' => $this->lokasi_tujuan,
            'tipe_layanan' => $this->tipe_layanan,
            'biaya_layanan' => $this->biaya_layanan,
            'dana_pembelian' => $this->dana_pembelian,
            'catatan_dana_pembelian' => $this->catatan_dana_pembelian,
            'status' => 'baru',
        ]);

        $this->simpanDataPetaTambahan($permintaan);

        LogStatusPermintaan::create([
            'permintaan_layanan_id' => $permintaan->id,
            'user_id' => Auth::id(),
            'status_lama' => null,
            'status_baru' => 'baru',
            'catatan' => 'Permintaan dibuat oleh user melalui website.',
        ]);

        $this->kirimNotifikasiAdmin($permintaan);

        $permintaan->deskripsi = $this->buatDeskripsiWhatsapp();

        $whatsappUrl = $permintaan->whatsapp_url;
        $kodeBerhasil = $permintaan->kode;
        $noHpBerhasil = $permintaan->no_hp;

        $cekStatusUrl = url('/cek-status') . '?' . http_build_query([
            'kode' => $kodeBerhasil,
            'no_hp' => $noHpBerhasil,
        ]);

        $this->reset([
            'kategori_layanan_id',
            'nama_pemesan',
            'no_hp',
            'judul',
            'deskripsi',
            'lokasi_awal',
            'lokasi_tujuan',
            'lokasi_awal_lat',
            'lokasi_awal_lng',
            'lokasi_tujuan_lat',
            'lokasi_tujuan_lng',
            'estimasi_jarak_km',
            'biaya_dasar_layanan',
            'biaya_perjalanan',
            'estimasi_total_biaya',
            'biaya_layanan',
            'butuh_dana_pembelian',
            'dana_pembelian',
            'catatan_dana_pembelian',
        ]);

        $this->tipe_layanan = 'normal';
        $this->hitungBiayaLayanan();

        $this->kode_berhasil = $kodeBerhasil;
        $this->no_hp_berhasil = $noHpBerhasil;
        $this->whatsapp_url_berhasil = $whatsappUrl;
        $this->cek_status_url_berhasil = $cekStatusUrl;

        $this->pesan_sukses = 'Request berhasil dibuat. Kamu bisa langsung cek status request dari tombol di bawah.';

        $this->dispatch('reset-map-picker');
        $this->dispatch('buka-whatsapp-tab-baru', url: $whatsappUrl);
    }

    protected function buatDeskripsiWhatsapp(): string
    {
        $deskripsi = trim((string) $this->deskripsi);

        $linkRuteLengkap = $this->googleMapsRouteLink();

        $linkLokasiAwal = $this->googleMapsPointLink(
            $this->lokasi_awal_lat,
            $this->lokasi_awal_lng
        );

        $linkLokasiTujuan = $this->googleMapsPointLink(
            $this->lokasi_tujuan_lat,
            $this->lokasi_tujuan_lng
        );

        $catatan = [
            $deskripsi,
            '',
            '--- Estimasi Rute & Biaya ---',
            'Rute: Basecamp ESA Runner → Lokasi Awal/Eksekusi → Lokasi Tujuan',
            'Estimasi jarak: ' . number_format((float) $this->estimasi_jarak_km, 2, ',', '.') . ' km',
            'Biaya perjalanan: Rp ' . number_format((float) $this->biaya_perjalanan, 0, ',', '.'),
        ];

        if ($this->butuh_dana_pembelian) {
            $catatan[] = 'Dana pembelian: Rp ' . number_format((float) $this->dana_pembelian, 0, ',', '.');

            if (filled($this->catatan_dana_pembelian)) {
                $catatan[] = 'Catatan dana pembelian: ' . $this->catatan_dana_pembelian;
            }
        }

        $catatan[] = 'Total yang perlu disiapkan: Rp ' . number_format((float) $this->estimasi_total_biaya, 0, ',', '.');
        $catatan[] = 'Catatan: estimasi masih dapat disesuaikan setelah verifikasi admin.';

        if ($linkRuteLengkap) {
            $catatan[] = '';
            $catatan[] = 'Link Rute Lengkap: ' . $linkRuteLengkap;
        }

        if ($linkLokasiAwal) {
            $catatan[] = 'Link Maps Lokasi Awal: ' . $linkLokasiAwal;
        }

        if ($linkLokasiTujuan) {
            $catatan[] = 'Link Maps Lokasi Tujuan: ' . $linkLokasiTujuan;
        }

        return trim(
            implode(
                PHP_EOL,
                array_filter($catatan, fn ($item) => $item !== null)
            )
        );
    }

    protected function googleMapsPointLink($lat, $lng): ?string
    {
        if (! is_numeric($lat) || ! is_numeric($lng)) {
            return null;
        }

        return 'https://www.google.com/maps?q=' . (float) $lat . ',' . (float) $lng;
    }

    protected function googleMapsRouteLink(): ?string
    {
        if (
            ! is_numeric($this->lokasi_awal_lat) ||
            ! is_numeric($this->lokasi_awal_lng) ||
            ! is_numeric($this->lokasi_tujuan_lat) ||
            ! is_numeric($this->lokasi_tujuan_lng)
        ) {
            return null;
        }

        $origin = '-6.2728365,106.5265246';

        $waypoint =
            (float) $this->lokasi_awal_lat .
            ',' .
            (float) $this->lokasi_awal_lng;

        $destination =
            (float) $this->lokasi_tujuan_lat .
            ',' .
            (float) $this->lokasi_tujuan_lng;

        return 'https://www.google.com/maps/dir/?api=1' .
            '&origin=' . $origin .
            '&waypoints=' . $waypoint .
            '&destination=' . $destination .
            '&travelmode=driving';
    }

    protected function simpanDataPetaTambahan(PermintaanLayanan $permintaan): void
    {
        $table = $permintaan->getTable();

        $dataPeta = [
            'sumber' => 'openstreetmap_leaflet_osrm',
            'basecamp' => [
                'nama' => 'Basecamp ESA Runner',
                'lat' => -6.2728365,
                'lng' => 106.5265246,
            ],
            'lokasi_awal' => [
                'alamat' => $this->lokasi_awal,
                'lat' => $this->lokasi_awal_lat,
                'lng' => $this->lokasi_awal_lng,
            ],
            'lokasi_tujuan' => [
                'alamat' => $this->lokasi_tujuan,
                'lat' => $this->lokasi_tujuan_lat,
                'lng' => $this->lokasi_tujuan_lng,
            ],
            'estimasi_jarak_km' => (float) $this->estimasi_jarak_km,
            'biaya_dasar_layanan' => (float) $this->biaya_dasar_layanan,
            'biaya_perjalanan' => (float) $this->biaya_perjalanan,
            'dana_pembelian' => (float) $this->dana_pembelian,
            'catatan_dana_pembelian' => $this->catatan_dana_pembelian,
            'estimasi_total_biaya' => (float) $this->estimasi_total_biaya,
            'tarif' => [
                'km_pertama' => (float) $this->tarif_km_pertama,
                'km_berikutnya' => (float) $this->tarif_km_berikutnya,
            ],
        ];

        $extraData = [];

        $fieldMap = [
            'lokasi_awal_lat' => $this->lokasi_awal_lat,
            'lokasi_awal_lng' => $this->lokasi_awal_lng,
            'lokasi_tujuan_lat' => $this->lokasi_tujuan_lat,
            'lokasi_tujuan_lng' => $this->lokasi_tujuan_lng,
            'estimasi_jarak_km' => $this->estimasi_jarak_km,
            'total_jarak_km' => $this->estimasi_jarak_km,
            'jarak_km' => $this->estimasi_jarak_km,
            'biaya_dasar_layanan' => $this->biaya_dasar_layanan,
            'biaya_perjalanan' => $this->biaya_perjalanan,
            'dana_pembelian' => $this->dana_pembelian,
            'catatan_dana_pembelian' => $this->catatan_dana_pembelian,
            'estimasi_total_biaya' => $this->estimasi_total_biaya,
            'total_biaya' => $this->estimasi_total_biaya,
            'total_biaya_jasa' => $this->estimasi_total_biaya,
            'sumber_jarak' => 'api',
            'status_api_maps' => 'Rute otomatis dari OpenStreetMap/OSRM',
        ];

        foreach ($fieldMap as $field => $value) {
            if (Schema::hasColumn($table, $field)) {
                $extraData[$field] = $value;
            }
        }

        if (Schema::hasColumn($table, 'data_peta')) {
            $extraData['data_peta'] = $permintaan->hasCast('data_peta')
                ? $dataPeta
                : json_encode($dataPeta);
        }

        if ($extraData === []) {
            return;
        }

        $permintaan->forceFill($extraData)->save();
    }

    protected function kirimNotifikasiAdmin(PermintaanLayanan $permintaan): void
    {
        $adminUsers = User::query()->get();

        if ($adminUsers->isEmpty()) {
            return;
        }

        FilamentNotification::make()
            ->title('Orderan Baru Masuk')
            ->body(
                $permintaan->nama_pemesan .
                ' membuat request "' .
                $permintaan->judul .
                '" dengan kode ' .
                $permintaan->kode .
                '.'
            )
            ->icon('heroicon-o-bell-alert')
            ->iconColor('warning')
            ->actions([
                Action::make('lihat')
                    ->label('Lihat Request')
                    ->button()
                    ->url(
                        PermintaanLayananResource::getUrl(
                            'edit',
                            ['record' => $permintaan],
                            panel: 'admin'
                        )
                    ),
            ])
            ->sendToDatabase($adminUsers);
    }

    public function tutupModalBerhasil(): void
    {
        $this->reset([
            'pesan_sukses',
            'kode_berhasil',
            'no_hp_berhasil',
            'whatsapp_url_berhasil',
            'cek_status_url_berhasil',
        ]);
    }

    public function render()
    {
        return view('livewire.form-permintaan-layanan', [
            'kategoriLayanan' => KategoriLayanan::query()
                ->orderBy('nama')
                ->get(),
        ]);
    }
}