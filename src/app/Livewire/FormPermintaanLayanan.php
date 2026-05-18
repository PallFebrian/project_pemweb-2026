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
    public $biaya_layanan = 0;

    public $pesan_sukses = '';

    public function mount(): void
    {
        $this->hitungBiayaLayanan();
    }

    public function updatedKategoriLayananId(): void
    {
        $this->hitungBiayaLayanan();
    }

    public function updatedTipeLayanan(): void
    {
        $this->hitungBiayaLayanan();
    }

    public function hitungBiayaLayanan(): void
    {
        $kategori = KategoriLayanan::find($this->kategori_layanan_id);

        if (! $kategori) {
            $this->biaya_layanan = 0;
            return;
        }

        $this->biaya_layanan = $this->tipe_layanan === 'express'
            ? (float) $kategori->biaya_express
            : (float) $kategori->biaya_normal;
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
            'nama_pemesan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'lokasi_awal' => ['nullable', 'string', 'max:255'],
            'lokasi_tujuan' => ['nullable', 'string', 'max:255'],
            'tipe_layanan' => ['required', 'in:normal,express'],
        ]);

        $this->hitungBiayaLayanan();

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
            'status' => 'baru',
        ]);

        LogStatusPermintaan::create([
             'permintaan_layanan_id' => $permintaan->id,
             'user_id' => Auth::id(),
             'status_lama' => null,
             'status_baru' => 'baru',
             'catatan' => 'Permintaan dibuat oleh user melalui website.',
        ]);

        $this->kirimNotifikasiAdmin($permintaan);

        $whatsappUrl = $permintaan->whatsapp_url;

        $this->reset([
            'kategori_layanan_id',
            'nama_pemesan',
            'no_hp',
            'judul',
            'deskripsi',
            'lokasi_awal',
            'lokasi_tujuan',
            'biaya_layanan',
        ]);

        $this->tipe_layanan = 'normal';

        $this->pesan_sukses = 'Request berhasil dibuat. WhatsApp admin dibuka di tab baru.';

        $this->dispatch('buka-whatsapp-tab-baru', url: $whatsappUrl);
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

    public function render()
    {
        return view('livewire.form-permintaan-layanan', [
            'kategoriLayanan' => KategoriLayanan::query()
                ->orderBy('nama')
                ->get(),
        ]);
    }
}