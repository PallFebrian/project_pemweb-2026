<div class="status-card">
    <style>
        .result-top {
            align-items: center;
        }

        .result-top-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .refresh-icon-btn {
            width: 42px;
            height: 42px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #ff7a00;
            color: white;
            border: 1px solid #ff7a00;
            box-shadow: 0 12px 30px rgba(255, 122, 0, .22);
            transition: .2s ease;
        }

        .refresh-icon-btn:hover {
            background: #e86b00;
            transform: rotate(-18deg);
        }

        .refresh-icon-btn svg {
            width: 19px;
            height: 19px;
        }

        .change-request-btn {
            min-height: 40px;
            padding: 0 15px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #0b2a66;
            border: 1px solid #dbe4f0;
            box-shadow: none;
            font-size: 13px;
            font-weight: 900;
        }

        .change-request-btn:hover {
            background: #e2e8f0;
        }

        .refresh-message {
            margin-top: 14px;
            padding: 12px 14px;
            border-radius: 14px;
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
            color: #166534;
            font-size: 13px;
            font-weight: 800;
        }

        .empty-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .empty-content {
            flex: 1;
        }

        @media (max-width: 860px) {
            .result-top {
                align-items: flex-start;
            }

            .result-top-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .change-request-btn {
                flex: 1;
            }

            .empty-top {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
        }
    </style>

    <div class="status-header">
        <span class="eyebrow">Cek Status Request</span>
        <h1>Pantau permintaan kamu</h1>
        <p>
            Masukkan kode request dan nomor WhatsApp yang digunakan saat membuat permintaan.
        </p>
    </div>

    <form wire:submit.prevent="cari" class="status-form">
        <div class="form-group">
            <label>Kode Request</label>
            <input
                type="text"
                wire:model="kode"
                placeholder="Contoh: REQ-20260515-ABCDE"
            >
            @error('kode')
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

        <button type="submit" wire:loading.attr="disabled" wire:target="cari">
            <span wire:loading.remove wire:target="cari">Cek Status</span>
            <span wire:loading wire:target="cari">Mencari...</span>
        </button>
    </form>

    @if ($pesanRefresh)
        <div class="refresh-message">
            {{ $pesanRefresh }}
        </div>
    @endif

    @if ($sudahDicari && ! $permintaan)
        <div class="empty-result">
            <div class="empty-top">
                <div class="empty-content">
                    <div class="empty-icon">!</div>
                    <h2>Data tidak ditemukan</h2>
                    <p>
                        Pastikan kode request dan nomor WhatsApp sudah sesuai dengan data saat membuat permintaan.
                    </p>
                </div>

                <button
                    type="button"
                    class="change-request-btn"
                    wire:click="resetPencarian"
                >
                    Reset Pencarian
                </button>
            </div>
        </div>
    @endif

    @if ($permintaan)
        @php
            $statusLabel = match ($permintaan->status) {
                'baru' => 'Baru',
                'diproses' => 'Diproses',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
                default => ucfirst($permintaan->status),
            };

            $statusClass = match ($permintaan->status) {
                'baru' => 'status-blue',
                'diproses' => 'status-yellow',
                'selesai' => 'status-green',
                'dibatalkan' => 'status-red',
                default => 'status-gray',
            };
        @endphp

        <div class="result-card">
            <div class="result-top">
                <div>
                    <span class="result-label">Kode Request</span>
                    <h2>{{ $permintaan->kode }}</h2>
                </div>

                <div class="result-top-actions">
                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>

                    <button
                        type="button"
                        class="refresh-icon-btn"
                        wire:click="refreshStatus"
                        wire:loading.attr="disabled"
                        wire:target="refreshStatus"
                        title="Refresh status"
                    >
                        <span wire:loading.remove wire:target="refreshStatus">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 4v6h6M20 20v-6h-6M5.6 15.5A7.5 7.5 0 0 0 18.4 18M18.4 8.5A7.5 7.5 0 0 0 5.6 6" />
                            </svg>
                        </span>

                        <span wire:loading wire:target="refreshStatus">
                            ...
                        </span>
                    </button>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span>Nama Pemesan</span>
                    <strong>{{ $permintaan->nama_pemesan }}</strong>
                </div>

                <div class="info-item">
                    <span>Nomor WhatsApp</span>
                    <strong>{{ $permintaan->no_hp }}</strong>
                </div>

                <div class="info-item">
                    <span>Kategori</span>
                    <strong>{{ $permintaan->kategoriLayanan?->nama ?? '-' }}</strong>
                </div>

                <div class="info-item">
                    <span>Tipe Layanan</span>
                    <strong>{{ ucfirst($permintaan->tipe_layanan) }}</strong>
                </div>

                <div class="info-item">
                    <span>Biaya Layanan</span>
                    <strong>Rp {{ number_format((float) $permintaan->biaya_layanan, 0, ',', '.') }}</strong>
                </div>

                <div class="info-item">
                    <span>Tanggal Request</span>
                    <strong>{{ $permintaan->created_at?->format('d M Y H:i') }}</strong>
                </div>
            </div>

            <div class="detail-box">
                <span>Judul Permintaan</span>
                <p>{{ $permintaan->judul }}</p>
            </div>

            <div class="detail-box">
                <span>Deskripsi</span>
                <p>{{ $permintaan->deskripsi ?: '-' }}</p>
            </div>

            <div class="location-grid">
                <div class="detail-box">
                    <span>Lokasi Awal</span>
                    <p>{{ $permintaan->lokasi_awal ?: '-' }}</p>
                </div>

                <div class="detail-box">
                    <span>Lokasi Tujuan</span>
                    <p>{{ $permintaan->lokasi_tujuan ?: '-' }}</p>
                </div>
            </div>

            @if ($permintaan->catatan_admin)
                <div class="admin-note">
                    <span>Catatan Admin</span>
                    <p>{{ $permintaan->catatan_admin }}</p>
                </div>
            @endif
        </div>
    @endif
</div>