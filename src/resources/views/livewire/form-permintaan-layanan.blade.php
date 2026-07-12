@php
    $kategoriTerpilih = $kategoriLayanan->firstWhere('id', (int) $kategori_layanan_id);

    $namaKategori = $kategoriTerpilih?->nama ?? 'Belum dipilih';
    $tipeLabel = $tipe_layanan === 'express' ? 'Express' : 'Normal';

    $jarakKm = (float) $estimasi_jarak_km;

    /*
     * Aturan tampilan biaya:
     * 1,54 KM => ditagihkan 1 KM
     * 2,30 KM => ditagihkan 2 KM
     * 3,90 KM => ditagihkan 3 KM
     */
    $jarakDitagihkanKm = $jarakKm > 0
        ? max(1, (int) floor($jarakKm))
        : 0;

    $kmBerikutnyaDibulatkan = $jarakDitagihkanKm > 1
        ? $jarakDitagihkanKm - 1
        : 0;

    $biayaKmPertamaTampil = $jarakKm > 0
        ? (float) $tarif_km_pertama
        : 0;

    $biayaKmBerikutnyaTampil =
        $kmBerikutnyaDibulatkan * (float) $tarif_km_berikutnya;

    $danaPembelianTampil = $butuh_dana_pembelian
        ? (float) $dana_pembelian
        : 0;
@endphp

<div class="form-permintaan-layanan-root">
    <style>
        .map-helper-card {
            grid-column: 1 / -1;
            padding: 18px;
            border: 1px solid #e4ecf7;
            border-radius: 22px;
            background: #f8fbff;
        }

        .map-helper-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 14px;
        }

        .map-helper-header h3 {
            margin: 0;
            color: #101f3d;
            font-size: 18px;
            letter-spacing: -0.3px;
        }

        .map-helper-header p {
            margin: 6px 0 0;
            color: #667085;
            font-size: 13px;
            line-height: 1.6;
        }

        .map-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            flex-shrink: 0;
            padding: 8px 11px;
            border-radius: 999px;
            color: #0b63f6;
            background: #eef5ff;
            font-size: 11px;
            font-weight: 900;
            white-space: nowrap;
        }

        .location-action-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto auto;
            gap: 9px;
        }

        .location-input-wrap {
            position: relative;
            min-width: 0;
        }

        .address-suggestions {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            left: 0;
            z-index: 80;
            display: none;
            max-height: 240px;
            overflow-y: auto;
            padding: 8px;
            border: 1px solid #d7e1ef;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(16, 33, 63, 0.16);
        }

        .address-suggestions.is-open {
            display: grid;
            gap: 6px;
        }

        .suggestion-item {
            width: 100%;
            display: block;
            padding: 10px 11px;
            border: none;
            border-radius: 12px;
            text-align: left;
            background: transparent;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background: #eef5ff;
        }

        .suggestion-title {
            display: block;
            color: #101f3d;
            font-size: 13px;
            font-weight: 900;
            line-height: 1.35;
        }

        .suggestion-subtitle {
            display: block;
            margin-top: 3px;
            color: #667085;
            font-size: 11px;
            line-height: 1.4;
        }

        .suggestion-empty {
            padding: 10px;
            color: #667085;
            font-size: 12px;
            line-height: 1.5;
        }

        .map-action {
            min-height: 50px;
            padding: 0 13px;
            border: 1px solid #d7e1ef;
            border-radius: 14px;
            color: #0b63f6;
            background: #ffffff;
            font-size: 12px;
            font-weight: 900;
            cursor: pointer;
            transition: 0.18s ease;
        }

        .map-action:hover,
        .map-action.is-active {
            border-color: #0b63f6;
            background: #eef5ff;
        }

        .request-map {
            width: 100%;
            height: 340px;
            overflow: hidden;
            border: 1px solid #d7e1ef;
            border-radius: 20px;
            background: #edf3fb;
        }

        .map-message {
            margin-top: 12px;
            padding: 12px 14px;
            border: 1px solid #e4ecf7;
            border-radius: 16px;
            color: #344054;
            background: #ffffff;
            font-size: 12px;
            line-height: 1.55;
        }

        .route-summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 14px;
        }

        .route-box {
            padding: 14px;
            border: 1px solid #e8f0fc;
            border-radius: 16px;
            background: #f8fbff;
        }

        .route-box span {
            display: block;
            margin-bottom: 6px;
            color: #667085;
            font-size: 11px;
            font-weight: 900;
        }

        .route-box strong {
            display: block;
            color: #101f3d;
            font-size: 14px;
            line-height: 1.4;
        }

        .cost-detail {
            display: grid;
            gap: 9px;
            margin-top: 14px;
        }

        .cost-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            color: #667085;
            font-size: 12px;
            line-height: 1.45;
        }

        .cost-row strong {
            color: #101f3d;
            font-size: 13px;
            white-space: nowrap;
        }

        .cost-row.total {
            padding-top: 12px;
            margin-top: 3px;
            border-top: 1px dashed rgba(11, 99, 246, 0.28);
            color: #101f3d;
            font-size: 13px;
            font-weight: 900;
        }

        .cost-row.total strong {
            color: #ff7a00;
            font-size: 18px;
        }

        .price-note {
            margin-top: 12px;
            color: #667085;
            font-size: 11px;
            line-height: 1.55;
        }

        .dana-pembelian-card {
            grid-column: 1 / -1;
            padding: 18px;
            border: 1px solid #fed7aa;
            border-radius: 22px;
            background:
                linear-gradient(
                    135deg,
                    rgba(255, 122, 0, 0.08),
                    rgba(11, 99, 246, 0.04)
                );
        }

        .dana-pembelian-header {
            margin-bottom: 16px;
        }

        .dana-pembelian-header h3 {
            margin: 0;
            color: #101f3d;
            font-size: 18px;
        }

        .dana-pembelian-header p {
            margin: 7px 0 0;
            color: #667085;
            font-size: 13px;
            line-height: 1.6;
        }

        .dana-pembelian-grid {
            display: grid;
            grid-template-columns: 0.75fr 1.25fr;
            gap: 14px;
        }

        .dana-note {
            margin-top: 12px;
            color: #9a3412;
            font-size: 12px;
            line-height: 1.5;
        }

        .cost-row.total {
            margin-top: 8px;
            padding-top: 10px;
            border-top: 1px dashed #cbd5e1;
        }

        .cost-row.total span,
        .cost-row.total strong {
            color: #101f3d;
            font-weight: 900;
        }

        .success-request-card {
            grid-column: 1 / -1;
            padding: 20px;
            margin-bottom: 18px;
            border: 1px solid rgba(22, 163, 106, 0.24);
            border-radius: 22px;
            background:
                linear-gradient(
                    135deg,
                    rgba(22, 163, 106, 0.10),
                    rgba(11, 99, 246, 0.05)
                );
        }

        .success-request-top {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .success-request-icon {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            border-radius: 16px;
            color: white;
            background: #16a36a;
            font-size: 20px;
            font-weight: 900;
        }

        .success-request-content {
            min-width: 0;
            flex: 1;
        }

        .success-request-content h3 {
            margin: 0;
            color: #101f3d;
            font-size: 19px;
        }

        .success-request-content p {
            margin: 7px 0 0;
            color: #667085;
            font-size: 13px;
            line-height: 1.6;
        }

        .success-code-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-top: 16px;
            padding: 14px 16px;
            border: 1px dashed rgba(22, 163, 106, 0.42);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.72);
        }

        .success-code-box span {
            display: block;
            color: #667085;
            font-size: 12px;
            font-weight: 800;
        }

        .success-code-box strong {
            display: block;
            margin-top: 4px;
            color: #101f3d;
            font-size: 18px;
            letter-spacing: 0.3px;
            overflow-wrap: anywhere;
        }

        .success-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
        }

        .success-action-btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 15px;
            border: none;
            border-radius: 14px;
            color: white;
            background: #0b63f6;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
            text-decoration: none;
        }

        .success-action-btn.copy {
            background: #16a36a;
        }

        .success-action-btn.whatsapp {
            background: #ff7a00;
        }

        .success-action-btn.secondary {
            color: #0b63f6;
            border: 1px solid #cfe0ff;
            background: #eef5ff;
        }

        .success-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: grid;
            place-items: center;
            padding: 22px;
            background: rgba(4, 21, 47, 0.48);
            backdrop-filter: blur(9px);
        }

        .success-modal-card {
            position: relative;
            width: min(560px, 100%);
            padding: 30px;
            border: 1px solid rgba(228, 236, 247, 0.95);
            border-radius: 30px;
            background: #ffffff;
            box-shadow: 0 30px 90px rgba(4, 21, 47, 0.28);
            animation: successModalIn 0.22s ease-out;
        }

        @keyframes successModalIn {
            from {
                opacity: 0;
                transform: translateY(14px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .success-modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border: 1px solid #e4ecf7;
            border-radius: 50%;
            color: #667085;
            background: #f8fbff;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
            cursor: pointer;
        }

        .success-modal-icon {
            width: 62px;
            height: 62px;
            display: grid;
            place-items: center;
            margin-bottom: 18px;
            border-radius: 22px;
            color: #ffffff;
            background: #16a36a;
            font-size: 30px;
            font-weight: 900;
            box-shadow: 0 16px 32px rgba(22, 163, 106, 0.22);
        }

        .success-modal-card h3 {
            margin: 0;
            color: #101f3d;
            font-size: 26px;
            line-height: 1.2;
        }

        .success-modal-card p {
            margin: 10px 0 0;
            color: #667085;
            font-size: 14px;
            line-height: 1.7;
        }

        .success-modal-code {
            margin-top: 20px;
            padding: 17px 18px;
            border: 1px dashed rgba(22, 163, 106, 0.45);
            border-radius: 18px;
            background:
                linear-gradient(
                    135deg,
                    rgba(22, 163, 106, 0.08),
                    rgba(11, 99, 246, 0.04)
                );
        }

        .success-modal-code span {
            display: block;
            color: #667085;
            font-size: 12px;
            font-weight: 900;
        }

        .success-modal-code strong {
            display: block;
            margin-top: 5px;
            color: #061637;
            font-size: 22px;
            letter-spacing: 0.4px;
            overflow-wrap: anywhere;
        }

        .success-modal-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
        }

        .success-modal-btn {
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            border-radius: 16px;
            color: #ffffff;
            background: #0b63f6;
            font-size: 14px;
            font-weight: 900;
            text-align: center;
            text-decoration: none;
        }

        .success-modal-btn.whatsapp {
            background: #ff7a00;
        }

        .success-modal-note {
            margin-top: 14px !important;
            color: #98a2b3 !important;
            font-size: 12px !important;
        }

        @media (max-width: 560px) {
            .success-modal-card {
                padding: 24px;
                border-radius: 24px;
            }

            .success-modal-actions {
                grid-template-columns: 1fr;
            }

            .success-modal-card h3 {
                font-size: 23px;
            }
        }

        @media (max-width: 640px) {
            .success-request-top {
                flex-direction: column;
            }

            .success-code-box {
                align-items: flex-start;
                flex-direction: column;
            }

            .success-action-btn {
                width: 100%;
            }
        }

        @media (max-width: 760px) {
            .dana-pembelian-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            .map-helper-header {
                display: block;
            }

            .map-status-pill {
                margin-top: 12px;
            }

            .location-action-row {
                grid-template-columns: 1fr;
            }

            .map-action {
                width: 100%;
            }

            .request-map {
                height: 300px;
            }

            .route-summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div
        id="esaRunnerMapConfig"
        data-livewire-id="{{ $this->getId() }}"
        data-base-lat="-6.2728365"
        data-base-lng="106.5265246"
        data-tarif-km-pertama="{{ $tarif_km_pertama }}"
        data-tarif-km-berikutnya="{{ $tarif_km_berikutnya }}"
        hidden
    ></div>

    <div class="form-shell">
        <div class="form-card">
            <div class="form-header">
                <h2>Detail Permintaan</h2>

                <p>
                    Lengkapi data berikut dengan jelas agar admin lebih mudah memahami request kamu.
                </p>
            </div>

            @if ($pesan_sukses && $kode_berhasil)
                <div class="success-modal-backdrop">
                    <div class="success-modal-card">
                        <button
                            type="button"
                            class="success-modal-close"
                            wire:click="tutupModalBerhasil"
                            aria-label="Tutup"
                        >
                            ×
                        </button>

                        <div class="success-modal-icon">
                            ✓
                        </div>

                        <h3>Request berhasil dibuat</h3>

                        <p>
                            Kode request kamu sudah dibuat. Kamu bisa langsung cek status
                            request tanpa perlu mengetik ulang kode dan nomor WhatsApp.
                            Kamu juga bisa membuka WhatsApp admin lewat tombol di bawah.
                        </p>

                        <div class="success-modal-code">
                            <span>Kode Request</span>
                            <strong>{{ $kode_berhasil }}</strong>
                        </div>

                        <div class="success-modal-actions">
                            <a
                                href="{{ $cek_status_url_berhasil ?: url('/cek-status') }}"
                                class="success-modal-btn"
                            >
                                Cek Status Request
                            </a>

                            @if ($whatsapp_url_berhasil)
                                <a
                                    href="{{ $whatsapp_url_berhasil }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="success-modal-btn whatsapp"
                                >
                                    Buka WhatsApp Admin
                                </a>
                            @endif
                        </div>

                        <p class="success-modal-note">
                            Simpan kode request ini kalau nanti ingin cek status secara manual.
                        </p>
                    </div>
                </div>
            @elseif ($pesan_sukses)
                <div class="alert alert-success">
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

                @if ($butuh_dana_pembelian)
                    <div class="dana-pembelian-card">
                        <div class="dana-pembelian-header">
                            <h3>Dana Pembelian</h3>

                            <p>
                                Layanan ini membutuhkan perkiraan uang pembelian.
                                Dana ini bukan biaya jasa, tapi uang yang akan dipakai
                                kurir untuk membayar barang atau makanan sesuai request kamu.
                            </p>
                        </div>

                        <div class="dana-pembelian-grid">
                            <div class="form-group">
                                <label>Estimasi Dana Pembelian</label>

                                <input
                                    type="number"
                                    min="0"
                                    step="1000"
                                    wire:model.live.debounce.500ms="dana_pembelian"
                                    placeholder="Contoh: 25000"
                                >

                                @error('dana_pembelian')
                                    <small>{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Catatan Dana Pembelian</label>

                                <input
                                    type="text"
                                    wire:model="catatan_dana_pembelian"
                                    placeholder="Contoh: ayam geprek + es teh, maksimal Rp25.000"
                                >

                                @error('catatan_dana_pembelian')
                                    <small>{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="dana-note">
                            Dana pembelian dapat berubah setelah admin melakukan verifikasi,
                            misalnya jika harga barang atau makanan berbeda dari perkiraan.
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label>Lokasi Awal / Eksekusi</label>

                    <div class="location-action-row">
                        <div class="location-input-wrap">
                            <input
                                id="lokasiAwalInput"
                                type="text"
                                wire:model.live.debounce.700ms="lokasi_awal"
                                placeholder="Ketik alamat / pilih suggestion / klik titik map"
                                autocomplete="off"
                            >

                            <div
                                id="lokasiAwalSuggestions"
                                class="address-suggestions"
                                wire:ignore
                            ></div>
                        </div>

                        <button
                            type="button"
                            class="map-action"
                            data-search-target="awal"
                        >
                            Cari di Map
                        </button>

                        <button
                            type="button"
                            class="map-action"
                            data-pick-target="awal"
                        >
                            Pilih Titik
                        </button>
                    </div>

                    @error('lokasi_awal')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Lokasi Tujuan</label>

                    <div class="location-action-row">
                        <div class="location-input-wrap">
                            <input
                                id="lokasiTujuanInput"
                                type="text"
                                wire:model.live.debounce.700ms="lokasi_tujuan"
                                placeholder="Ketik alamat / pilih suggestion / klik titik map"
                                autocomplete="off"
                            >

                            <div
                                id="lokasiTujuanSuggestions"
                                class="address-suggestions"
                                wire:ignore
                            ></div>
                        </div>

                        <button
                            type="button"
                            class="map-action"
                            data-search-target="tujuan"
                        >
                            Cari di Map
                        </button>

                        <button
                            type="button"
                            class="map-action"
                            data-pick-target="tujuan"
                        >
                            Pilih Titik
                        </button>
                    </div>

                    @error('lokasi_tujuan')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <input type="hidden" wire:model="lokasi_awal_lat">
                <input type="hidden" wire:model="lokasi_awal_lng">
                <input type="hidden" wire:model="lokasi_tujuan_lat">
                <input type="hidden" wire:model="lokasi_tujuan_lng">
                <input type="hidden" wire:model="estimasi_jarak_km">

                <div class="map-helper-card">
                    <div class="map-helper-header">
                        <div>
                            <h3>Pilih Titik Lokasi dari Map</h3>

                            <p>
                                Ketik alamat untuk melihat suggestion, atau klik tombol
                                <b>Pilih Titik</b> lalu tekan posisi langsung di map.
                                Marker bisa digeser kalau titiknya belum pas.
                            </p>
                        </div>

                        <div class="map-status-pill">
                            📍 Basecamp → Awal → Tujuan
                        </div>
                    </div>

                    <div
                        id="requestMap"
                        class="request-map"
                        wire:ignore
                    ></div>

                    <div
                        id="mapStatus"
                        class="map-message"
                        wire:ignore
                    >
                        Pilih titik lokasi awal dan lokasi tujuan untuk menghitung estimasi rute.
                    </div>
                </div>

                <button
                    type="submit"
                    class="submit-btn"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Kirim Request via WhatsApp</span>
                    <span wire:loading>Menyimpan Request...</span>
                </button>
            </form>
        </div>

        <aside class="side-card">
            <div class="side-top">
                <div class="side-logo">
                    <img
                        src="{{ asset('images/logo-esgul-suruh.png') }}"
                        alt="Logo ESA Runner"
                    >
                </div>

                <div>
                    <h3>
                        Ringkasan <span>Request</span>
                    </h3>

                    <p>
                        Cek kategori, tipe layanan, estimasi jarak, dan biaya sebelum dikirim.
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

                <div class="route-summary-grid">
                    <div class="route-box">
                        <span>Estimasi Jarak</span>

                        <strong>
                            @if ($jarakKm > 0)
                                {{ number_format($jarakKm, 2, ',', '.') }} KM
                            @else
                                Belum dihitung
                            @endif
                        </strong>
                    </div>

                    <div class="route-box">
                        <span>Rute</span>
                        <strong>Basecamp → Awal → Tujuan</strong>
                    </div>
                </div>

                <div class="price-summary">
                    <span>Total yang Perlu Disiapkan</span>

                    <strong>
                        Rp {{ number_format((float) $estimasi_total_biaya, 0, ',', '.') }}
                    </strong>

                    <div class="cost-detail">
                        <div class="cost-row">
                            <span>KM pertama</span>

                            <strong>
                                Rp {{ number_format($biayaKmPertamaTampil, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="cost-row">
                            <span>
                                KM berikutnya
                                @if ($jarakDitagihkanKm > 1)
                                    — dihitung {{ $kmBerikutnyaDibulatkan }} KM
                                @endif
                            </span>

                            <strong>
                                Rp {{ number_format($biayaKmBerikutnyaTampil, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="cost-row">
                            <span>Biaya perjalanan</span>

                            <strong>
                                Rp {{ number_format((float) $biaya_perjalanan, 0, ',', '.') }}
                            </strong>
                        </div>

                        @if ($butuh_dana_pembelian)
                            <div class="cost-row">
                                <span>Dana pembelian</span>

                                <strong>
                                    Rp {{ number_format($danaPembelianTampil, 0, ',', '.') }}
                                </strong>
                            </div>
                        @endif

                        <div class="cost-row total">
                            <span>Total yang perlu disiapkan</span>

                            <strong>
                                Rp {{ number_format((float) $estimasi_total_biaya, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>

                    <div class="price-note">
                        Tarif perjalanan: KM pertama Rp {{ number_format((float) $tarif_km_pertama, 0, ',', '.') }},
                        KM berikutnya Rp {{ number_format((float) $tarif_km_berikutnya, 0, ',', '.') }}/KM.
                        Jarak desimal dibulatkan ke bawah untuk perhitungan biaya.

                        @if ($butuh_dana_pembelian)
                            Dana pembelian bukan biaya jasa dan digunakan untuk membayar kebutuhan sesuai request.
                        @endif

                        Estimasi masih dapat disesuaikan setelah admin melakukan verifikasi.
                    </div>
                </div>

                <ul class="helper-list">
                    <li>
                        <b>✓</b>
                        <span>Pastikan nomor WhatsApp aktif dan bisa dihubungi.</span>
                    </li>

                    <li>
                        <b>✓</b>
                        <span>Isi lokasi awal dan tujuan dengan jelas, pilih suggestion, atau pilih langsung dari map.</span>
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

        if (!window.esaRunnerRequestMapBooted) {
            window.esaRunnerRequestMapBooted = true;

            const startMapBoot = function () {
                loadLeaflet(function () {
                    initRequestMap();
                });
            };

            if (window.Livewire) {
                startMapBoot();
            } else {
                document.addEventListener('livewire:init', startMapBoot);
            }
        }

        function loadLeaflet(callback) {
            if (window.L) {
                callback();
                return;
            }

            if (!document.querySelector('link[data-leaflet-css]')) {
                const leafletCss = document.createElement('link');
                leafletCss.rel = 'stylesheet';
                leafletCss.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                leafletCss.dataset.leafletCss = 'true';
                document.head.appendChild(leafletCss);
            }

            if (document.querySelector('script[data-leaflet-js]')) {
                const waitLeaflet = setInterval(function () {
                    if (window.L) {
                        clearInterval(waitLeaflet);
                        callback();
                    }
                }, 100);

                return;
            }

            const leafletJs = document.createElement('script');
            leafletJs.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            leafletJs.dataset.leafletJs = 'true';
            leafletJs.onload = callback;
            document.body.appendChild(leafletJs);
        }

        function initRequestMap() {
            const config = document.getElementById('esaRunnerMapConfig');
            const mapElement = document.getElementById('requestMap');
            const statusElement = document.getElementById('mapStatus');

            if (!config || !mapElement || !window.L) {
                return;
            }

            if (mapElement.dataset.ready === 'true') {
                return;
            }

            mapElement.dataset.ready = 'true';

            const livewireId = config.dataset.livewireId;
            const baseLat = parseFloat(config.dataset.baseLat);
            const baseLng = parseFloat(config.dataset.baseLng);

            const basecamp = {
                lat: baseLat,
                lng: baseLng,
            };

            const state = {
                activeTarget: 'awal',
                awal: null,
                tujuan: null,
                awalMarker: null,
                tujuanMarker: null,
                routeLayer: null,
                baseMarker: null,
                suggestionTimer: null,
            };

            const map = L.map(mapElement, {
                scrollWheelZoom: true,
            }).setView([basecamp.lat, basecamp.lng], 14);

            L.tileLayer(
                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors',
                }
            ).addTo(map);

            state.baseMarker = L.marker([basecamp.lat, basecamp.lng])
                .addTo(map)
                .bindPopup('Basecamp ESA Runner');

            setStatus('Ketik alamat untuk melihat suggestion, atau pilih titik langsung dari map.');

            setTimeout(function () {
                map.invalidateSize();
            }, 400);

            document.addEventListener('click', function (event) {
                const pickButton = event.target.closest('[data-pick-target]');
                const searchButton = event.target.closest('[data-search-target]');
                const suggestionButton = event.target.closest('[data-address-suggestion]');

                if (pickButton) {
                    const target = pickButton.dataset.pickTarget;

                    setActiveTarget(target);

                    const label = target === 'awal'
                        ? 'lokasi awal / eksekusi'
                        : 'lokasi tujuan';

                    setStatus('Mode pilih titik aktif. Klik map untuk menentukan ' + label + '.');
                    return;
                }

                if (searchButton) {
                    searchAddress(searchButton.dataset.searchTarget);
                    return;
                }

                if (suggestionButton) {
                    const target = suggestionButton.dataset.target;
                    const lat = parseFloat(suggestionButton.dataset.lat);
                    const lng = parseFloat(suggestionButton.dataset.lng);
                    const address = suggestionButton.dataset.address;

                    hideSuggestions(target);
                    updateLivewireAddress(target, address);
                    setPoint(target, lat, lng, false);
                    map.setView([lat, lng], 16);

                    setStatus('Alamat dari suggestion dipilih. Marker sudah dipasang di map.');
                    return;
                }

                if (!event.target.closest('.location-input-wrap')) {
                    hideSuggestions('awal');
                    hideSuggestions('tujuan');
                }
            });

            document.addEventListener('input', function (event) {
                if (
                    event.target.id !== 'lokasiAwalInput' &&
                    event.target.id !== 'lokasiTujuanInput'
                ) {
                    return;
                }

                const target = event.target.id === 'lokasiAwalInput'
                    ? 'awal'
                    : 'tujuan';

                const query = event.target.value.trim();

                clearTimeout(state.suggestionTimer);

                state.suggestionTimer = setTimeout(function () {
                    loadSuggestions(target, query);
                }, 650);
            });

            map.on('click', function (event) {
                setPoint(
                    state.activeTarget,
                    event.latlng.lat,
                    event.latlng.lng,
                    true
                );
            });

            window.addEventListener('reset-map-picker', function () {
                resetMap();
            });

            function livewireComponent() {
                if (!window.Livewire || !livewireId) {
                    return null;
                }

                return window.Livewire.find(livewireId);
            }

            function setActiveTarget(target) {
                state.activeTarget = target;

                document.querySelectorAll('[data-pick-target]').forEach(function (button) {
                    button.classList.toggle(
                        'is-active',
                        button.dataset.pickTarget === target
                    );
                });
            }

            function setStatus(message) {
                if (!statusElement) {
                    return;
                }

                statusElement.textContent = message;
            }

            function suggestionElement(target) {
                return target === 'awal'
                    ? document.getElementById('lokasiAwalSuggestions')
                    : document.getElementById('lokasiTujuanSuggestions');
            }

            function hideSuggestions(target) {
                const element = suggestionElement(target);

                if (!element) {
                    return;
                }

                element.classList.remove('is-open');
                element.innerHTML = '';
            }

            function showSuggestionMessage(target, message) {
                const element = suggestionElement(target);

                if (!element) {
                    return;
                }

                element.innerHTML = '';

                const empty = document.createElement('div');
                empty.className = 'suggestion-empty';
                empty.textContent = message;

                element.appendChild(empty);
                element.classList.add('is-open');
            }

            async function loadSuggestions(target, query) {
                if (query.length < 3) {
                    hideSuggestions(target);
                    return;
                }

                try {
                    showSuggestionMessage(target, 'Mencari suggestion alamat...');

                    const url =
                        'https://nominatim.openstreetmap.org/search' +
                        '?format=jsonv2' +
                        '&addressdetails=1' +
                        '&limit=5' +
                        '&countrycodes=id' +
                        '&q=' + encodeURIComponent(query);

                    const response = await fetch(url);
                    const results = await response.json();

                    renderSuggestions(target, results);
                } catch (error) {
                    showSuggestionMessage(
                        target,
                        'Suggestion belum bisa dimuat. Coba klik Cari di Map atau pilih titik langsung.'
                    );
                }
            }

            function renderSuggestions(target, results) {
                const element = suggestionElement(target);

                if (!element) {
                    return;
                }

                element.innerHTML = '';

                if (!Array.isArray(results) || results.length === 0) {
                    showSuggestionMessage(
                        target,
                        'Alamat belum ditemukan. Coba tulis lebih jelas atau pilih titik langsung di map.'
                    );

                    return;
                }

                results.forEach(function (result) {
                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);
                    const address = result.display_name || '';

                    if (!lat || !lng || !address) {
                        return;
                    }

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'suggestion-item';
                    button.dataset.addressSuggestion = 'true';
                    button.dataset.target = target;
                    button.dataset.lat = lat;
                    button.dataset.lng = lng;
                    button.dataset.address = address;

                    const title = document.createElement('span');
                    title.className = 'suggestion-title';
                    title.textContent = result.name || address.split(',')[0];

                    const subtitle = document.createElement('span');
                    subtitle.className = 'suggestion-subtitle';
                    subtitle.textContent = address;

                    button.appendChild(title);
                    button.appendChild(subtitle);
                    element.appendChild(button);
                });

                element.classList.add('is-open');
            }

            function setPoint(target, lat, lng, shouldReverseGeocode = true) {
                const markerKey = target === 'awal'
                    ? 'awalMarker'
                    : 'tujuanMarker';

                const label = target === 'awal'
                    ? 'Lokasi Awal / Eksekusi'
                    : 'Lokasi Tujuan';

                state[target] = {
                    lat: lat,
                    lng: lng,
                };

                if (!state[markerKey]) {
                    state[markerKey] = L.marker([lat, lng], {
                        draggable: true,
                    }).addTo(map);

                    state[markerKey].on('dragend', function () {
                        const position = state[markerKey].getLatLng();

                        setPoint(
                            target,
                            position.lat,
                            position.lng,
                            true
                        );
                    });
                } else {
                    state[markerKey].setLatLng([lat, lng]);
                }

                state[markerKey].bindPopup(label).openPopup();

                updateLivewireCoordinate(target, lat, lng);

                if (shouldReverseGeocode) {
                    reverseGeocode(target, lat, lng);
                }

                if (target === 'awal') {
                    setActiveTarget('tujuan');
                }

                calculateRouteIfReady();
            }

            function updateLivewireCoordinate(target, lat, lng) {
                const component = livewireComponent();

                if (!component) {
                    return;
                }

                if (target === 'awal') {
                    component.set('lokasi_awal_lat', lat);
                    component.set('lokasi_awal_lng', lng);
                } else {
                    component.set('lokasi_tujuan_lat', lat);
                    component.set('lokasi_tujuan_lng', lng);
                }
            }

            function updateLivewireAddress(target, address) {
                const component = livewireComponent();

                if (!component) {
                    return;
                }

                const input = target === 'awal'
                    ? document.getElementById('lokasiAwalInput')
                    : document.getElementById('lokasiTujuanInput');

                if (input) {
                    input.value = address;
                }

                if (target === 'awal') {
                    component.set('lokasi_awal', address);
                } else {
                    component.set('lokasi_tujuan', address);
                }
            }

            async function reverseGeocode(target, lat, lng) {
                try {
                    setStatus('Membaca alamat dari titik map...');

                    const url =
                        'https://nominatim.openstreetmap.org/reverse' +
                        '?format=jsonv2' +
                        '&lat=' + encodeURIComponent(lat) +
                        '&lon=' + encodeURIComponent(lng);

                    const response = await fetch(url);
                    const data = await response.json();

                    const address = data.display_name
                        ? data.display_name
                        : lat.toFixed(6) + ', ' + lng.toFixed(6);

                    updateLivewireAddress(target, address);

                    setStatus('Alamat berhasil dibaca. Lengkapi titik lain untuk menghitung rute.');
                } catch (error) {
                    updateLivewireAddress(
                        target,
                        lat.toFixed(6) + ', ' + lng.toFixed(6)
                    );

                    setStatus('Alamat belum bisa dibaca otomatis, tapi titik koordinat sudah tersimpan.');
                }
            }

            async function searchAddress(target) {
                const input = target === 'awal'
                    ? document.getElementById('lokasiAwalInput')
                    : document.getElementById('lokasiTujuanInput');

                const query = input ? input.value.trim() : '';

                if (!query) {
                    setStatus('Isi alamat terlebih dahulu, lalu klik Cari di Map.');
                    return;
                }

                try {
                    setStatus('Mencari alamat di map...');

                    const url =
                        'https://nominatim.openstreetmap.org/search' +
                        '?format=jsonv2' +
                        '&limit=1' +
                        '&countrycodes=id' +
                        '&q=' + encodeURIComponent(query);

                    const response = await fetch(url);
                    const results = await response.json();

                    if (!Array.isArray(results) || results.length === 0) {
                        setStatus('Alamat belum ditemukan. Coba tulis lebih jelas atau pilih titik langsung dari map.');
                        return;
                    }

                    const result = results[0];
                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);

                    updateLivewireAddress(target, result.display_name || query);
                    setPoint(target, lat, lng, false);

                    map.setView([lat, lng], 16);

                    setStatus('Alamat ditemukan. Marker sudah dipasang di map.');
                } catch (error) {
                    setStatus('Gagal mencari alamat. Coba pilih titik langsung dari map.');
                }
            }

            async function calculateRouteIfReady() {
                const component = livewireComponent();

                if (!state.awal || !state.tujuan || !component) {
                    return;
                }

                try {
                    setStatus('Menghitung rute dan estimasi jarak...');

                    const coordinates = [
                        basecamp.lng + ',' + basecamp.lat,
                        state.awal.lng + ',' + state.awal.lat,
                        state.tujuan.lng + ',' + state.tujuan.lat,
                    ].join(';');

                    const url =
                        'https://router.project-osrm.org/route/v1/driving/' +
                        coordinates +
                        '?overview=full&geometries=geojson&alternatives=true&continue_straight=false&steps=false';

                    const response = await fetch(url);
                    const data = await response.json();

                    if (
                        !data.routes ||
                        !Array.isArray(data.routes) ||
                        data.routes.length === 0
                    ) {
                        setStatus('Rute belum ditemukan. Coba geser marker atau pilih titik yang lebih jelas.');
                        return;
                    }

                    const route = data.routes.reduce(function (shortest, current) {
                        return current.distance < shortest.distance ? current : shortest;
                    }, data.routes[0]);

                    const distanceKm = route.distance / 1000;

                    if (state.routeLayer) {
                        map.removeLayer(state.routeLayer);
                    }

                    state.routeLayer = L.geoJSON(route.geometry, {
                        style: {
                            color: '#0b63f6',
                            weight: 5,
                            opacity: 0.88,
                        },
                    }).addTo(map);

                    map.fitBounds(state.routeLayer.getBounds(), {
                        padding: [30, 30],
                    });

                    component.set('estimasi_jarak_km', distanceKm.toFixed(2));

                    setStatus(
                        'Rute terdekat berhasil dihitung: ' +
                        distanceKm.toFixed(2).replace('.', ',') +
                        ' KM. Estimasi biaya sudah diperbarui.'
                    );
                } catch (error) {
                    setStatus('Gagal menghitung rute otomatis. Periksa koneksi internet atau coba lagi.');
                }
            }

            function resetMap() {
                if (state.awalMarker) {
                    map.removeLayer(state.awalMarker);
                }

                if (state.tujuanMarker) {
                    map.removeLayer(state.tujuanMarker);
                }

                if (state.routeLayer) {
                    map.removeLayer(state.routeLayer);
                }

                state.awal = null;
                state.tujuan = null;
                state.awalMarker = null;
                state.tujuanMarker = null;
                state.routeLayer = null;

                hideSuggestions('awal');
                hideSuggestions('tujuan');

                setActiveTarget('awal');
                map.setView([basecamp.lat, basecamp.lng], 14);

                setStatus('Request berhasil dibuat. Map dikosongkan kembali.');
            }
        }
    </script>
</div>