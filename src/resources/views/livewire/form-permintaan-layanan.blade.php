@php
    $kategoriTerpilih = $kategoriLayanan->firstWhere('id', (int) $kategori_layanan_id);

    $namaKategori = $kategoriTerpilih?->nama ?? 'Belum dipilih';
    $tipeLabel = $tipe_layanan === 'express' ? 'Express' : 'Normal';
@endphp

<div class="form-shell">
    <div class="form-card">
        <div class="form-header">
            <h2>Detail Permintaan</h2>
            <p>
                Lengkapi data berikut dengan jelas agar admin lebih mudah memahami request kamu.
            </p>
        </div>

        @if ($pesan_sukses)
            <div style="
                margin-bottom: 20px;
                padding: 14px 16px;
                border-radius: 16px;
                background: #ecfdf3;
                border: 1px solid #bbf7d0;
                color: #166534;
                font-size: 14px;
                font-weight: 800;
                line-height: 1.5;
            ">
                {{ $pesan_sukses }}
            </div>
        @endif

        <form wire:submit.prevent="simpan" class="form-grid">
            <div class="form-group">
                <label>Nama Pemesan</label>
                <input
                    type="text"
                    wire:model="nama_pemesan"
                    placeholder="Contoh: Muhammad Rizky"
                >
                @error('nama_pemesan')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Nomor WhatsApp</label>
                <input
                    type="text"
                    wire:model="no_hp"
                    placeholder="Contoh: 081234567890"
                >
                @error('no_hp')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Kategori Layanan</label>
                <select wire:model.live="kategori_layanan_id">
                    <option value="">Pilih kategori layanan</option>

                    @foreach ($kategoriLayanan as $kategori)
                        <option value="{{ $kategori->id }}">
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_layanan_id')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Judul Permintaan</label>
                <input
                    type="text"
                    wire:model="judul"
                    placeholder="Contoh: Titip beli ayam geprek"
                >
                @error('judul')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group full">
                <label>Tipe Layanan</label>

                <div class="type-options">
                    <label class="type-option">
                        <input
                            type="radio"
                            wire:model.live="tipe_layanan"
                            value="normal"
                        >

                        <div class="type-box">
                            <div class="type-icon">🕘</div>

                            <div>
                                <strong>Normal</strong>
                                <span>
                                    Biaya lebih hemat dengan estimasi pengerjaan yang lebih fleksibel.
                                </span>
                            </div>
                        </div>
                    </label>

                    <label class="type-option">
                        <input
                            type="radio"
                            wire:model.live="tipe_layanan"
                            value="express"
                        >

                        <div class="type-box">
                            <div class="type-icon">⚡</div>

                            <div>
                                <strong>Express</strong>
                                <span>
                                    Layanan prioritas untuk request yang perlu diproses lebih cepat.
                                </span>
                            </div>
                        </div>
                    </label>
                </div>

                @error('tipe_layanan')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group full">
                <label>Deskripsi Permintaan</label>
                <textarea
                    wire:model="deskripsi"
                    rows="4"
                    placeholder="Tulis detail permintaan kamu. Contoh: titip beli ayam geprek level 2, nasi satu, es teh satu."
                ></textarea>
                @error('deskripsi')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Lokasi Awal</label>
                <input
                    type="text"
                    wire:model="lokasi_awal"
                    placeholder="Contoh: Kantin Kampus"
                >
                @error('lokasi_awal')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Lokasi Tujuan</label>
                <input
                    type="text"
                    wire:model="lokasi_tujuan"
                    placeholder="Contoh: Gedung Fakultas"
                >
                @error('lokasi_tujuan')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="submit-btn" wire:loading.attr="disabled">
                <span wire:loading.remove>Kirim Request via WhatsApp</span>
                <span wire:loading>Menyimpan Request...</span>
            </button>
        </form>
    </div>

    <aside class="side-card">
        <div class="side-top">
            <div class="side-logo">
                <img src="{{ asset('images/logo-esgul-suruh.png') }}" alt="Logo Esgul Suruh">
            </div>

            <div>
                <h3>
                    Ringkasan <span>Request</span>
                </h3>

                <p>
                    Cek kembali kategori, tipe layanan, dan estimasi biaya sebelum dikirim.
                </p>
            </div>
        </div>

        <div class="side-body">
            <div class="summary-box">
                <span>Kategori Layanan</span>
                <strong>{{ $namaKategori }}</strong>
            </div>

            <div class="summary-box">
                <span>Tipe Layanan</span>
                <strong>{{ $tipeLabel }}</strong>
            </div>

            <div class="price-summary">
                <span>Estimasi Biaya</span>
                <strong>Rp {{ number_format((float) $biaya_layanan, 0, ',', '.') }}</strong>
            </div>

            <ul class="helper-list">
                <li>
                    <b>✓</b>
                    <span>Pastikan nomor WhatsApp aktif dan bisa dihubungi.</span>
                </li>

                <li>
                    <b>✓</b>
                    <span>Isi lokasi awal dan tujuan dengan jelas.</span>
                </li>

                <li>
                    <b>✓</b>
                    <span>Setelah submit, sistem akan membuka WhatsApp admin di tab baru.</span>
                </li>
            </ul>
        </div>
    </aside>
</div>

<script>
    if (!window.esgulSuruhWhatsappListenerReady) {
        window.esgulSuruhWhatsappListenerReady = true;

        window.addEventListener('buka-whatsapp-tab-baru', function (event) {
            const whatsappUrl = event.detail.url;

            if (!whatsappUrl) {
                return;
            }

            window.open(whatsappUrl, '_blank', 'noopener,noreferrer');
        });
    }
</script>