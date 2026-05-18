<?php

namespace App\Livewire;

use App\Models\PermintaanLayanan;
use Livewire\Component;

class CekStatusPermintaan extends Component
{
    public string $kode = '';
    public string $no_hp = '';

    public bool $sudahDicari = false;
    public ?int $permintaanId = null;

    public string $pesanRefresh = '';

    public function cari(): void
    {
        $this->pesanRefresh = '';

        $this->validate([
            'kode' => ['required', 'string', 'max:50'],
            'no_hp' => ['required', 'string', 'max:30'],
        ], [
            'kode.required' => 'Kode request wajib diisi.',
            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        $permintaan = PermintaanLayanan::query()
            ->where('kode', trim($this->kode))
            ->where('no_hp', trim($this->no_hp))
            ->first();

        $this->sudahDicari = true;
        $this->permintaanId = $permintaan?->id;
    }

    public function refreshStatus(): void
    {
        if (blank($this->kode) || blank($this->no_hp)) {
            $this->resetPencarian();
            return;
        }

        $permintaan = PermintaanLayanan::query()
            ->where('kode', trim($this->kode))
            ->where('no_hp', trim($this->no_hp))
            ->first();

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

    public function render()
    {
        $permintaan = $this->permintaanId
            ? PermintaanLayanan::with('kategoriLayanan')->find($this->permintaanId)
            : null;

        return view('livewire.cek-status-permintaan', [
            'permintaan' => $permintaan,
        ]);
    }
}