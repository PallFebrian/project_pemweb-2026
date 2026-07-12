<?php

namespace App\Livewire;

use App\Models\LogStatusPermintaan;
use App\Models\PermintaanLayanan;
use Livewire\Component;

class CekStatusPermintaan extends Component
{
    public string $kode = '';
    public string $no_hp = '';

    public bool $sudahDicari = false;
    public ?int $permintaanId = null;

    public string $pesanRefresh = '';

    public function mount(): void
    {
        $kode = request()->query('kode');
        $noHp = request()->query('no_hp');

        if (blank($kode) || blank($noHp)) {
            return;
        }

        $this->kode = strtoupper(trim((string) $kode));
        $this->no_hp = preg_replace('/\s+/', '', trim((string) $noHp));

        $permintaan = $this->ambilPermintaan();

        $this->sudahDicari = true;
        $this->permintaanId = $permintaan?->id;
    }

    public function cari(): void
    {
        $this->pesanRefresh = '';

        $this->kode = strtoupper(trim($this->kode));
        $this->no_hp = preg_replace('/\s+/', '', trim($this->no_hp));

        $this->validate([
            'kode' => ['required', 'string', 'max:50'],
            'no_hp' => ['required', 'string', 'max:30'],
        ], [
            'kode.required' => 'Kode request wajib diisi.',
            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        $permintaan = $this->ambilPermintaan();

        $this->sudahDicari = true;
        $this->permintaanId = $permintaan?->id;
    }

    public function refreshStatus(): void
    {
        if (blank($this->kode) || blank($this->no_hp)) {
            $this->resetPencarian();

            return;
        }

        $permintaan = $this->ambilPermintaan();

        $this->sudahDicari = true;
        $this->permintaanId = $permintaan?->id;
        $this->pesanRefresh = 'Status berhasil diperbarui pada ' . now()->format('H:i');
    }

    public function resetPencarian(): void
    {
        $this->reset([
            'kode',
            'no_hp',
            'sudahDicari',
            'permintaanId',
            'pesanRefresh',
        ]);
    }

    protected function ambilPermintaan(): ?PermintaanLayanan
    {
        return PermintaanLayanan::query()
            ->whereRaw('UPPER(kode) = ?', [strtoupper(trim($this->kode))])
            ->where('no_hp', preg_replace('/\s+/', '', trim($this->no_hp)))
            ->first();
    }

    public function render()
    {
        $permintaan = $this->permintaanId
            ? PermintaanLayanan::with('kategoriLayanan')->find($this->permintaanId)
            : null;

        $riwayatStatus = $permintaan
            ? LogStatusPermintaan::query()
                ->where('permintaan_layanan_id', $permintaan->id)
                ->latest()
                ->get()
            : collect();

        return view('livewire.cek-status-permintaan', [
            'permintaan' => $permintaan,
            'riwayatStatus' => $riwayatStatus,
        ]);
    }
}