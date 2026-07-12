<div class="cek-status-root">
    <div class="status-card">
        <div class="status-header">
            <div class="section-tag">
                Cek Request
            </div>

            <h2>
                Masukkan kode
                <span>request kamu</span>
            </h2>

            <p>
                Gunakan kode request dan nomor WhatsApp yang sama
                seperti saat membuat permintaan layanan.
            </p>
        </div>

        <form
            wire:submit.prevent="cari"
            class="status-form"
        >
            <div class="form-group">
                <label>Kode Request</label>

                <input
                    type="text"
                    wire:model="kode"
                    placeholder="Contoh: REQ-20260711-XXXXX"
                    autocomplete="off"
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
                    autocomplete="off"
                >

                @error('no_hp')
                    <small>{{ $message }}</small>
                @enderror
            </div>

            <button
                type="submit"
                class="submit-btn"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Cek Status</span>
                <span wire:loading>Mencari...</span>
            </button>
        </form>

        @if ($sudahDicari && ! $permintaan)
            <div class="empty-result">
                <div class="empty-icon">
                    !
                </div>

                <h3>Request tidak ditemukan</h3>

                <p>
                    Pastikan kode request dan nomor WhatsApp sudah sesuai.
                    Kalau baru saja membuat request, cek kembali kode yang
                    dikirim melalui WhatsApp.
                </p>
            </div>
        @endif

        @if ($permintaan)
            @php
                $status = (string) $permintaan->status;

                $statusLabel = match ($status) {
                    'baru' => 'Baru',
                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                    'diverifikasi' => 'Diverifikasi',
                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                    'dibayar' => 'Dibayar',
                    'ditugaskan' => 'Kurir Ditugaskan',
                    'diproses' => 'Diproses',
                    'diambil' => 'Barang Diambil',
                    'diantar' => 'Sedang Diantar',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                    'ditolak' => 'Ditolak',
                    default => \Illuminate\Support\Str::headline($status),
                };

                $statusClass = match ($status) {
                    'baru',
                    'menunggu_verifikasi' => 'status-blue',

                    'diverifikasi',
                    'menunggu_pembayaran',
                    'dibayar',
                    'ditugaskan',
                    'diproses',
                    'diambil',
                    'diantar' => 'status-yellow',

                    'selesai' => 'status-green',

                    'dibatalkan',
                    'ditolak' => 'status-red',

                    default => 'status-gray',
                };

                $tipeLabel = $permintaan->tipe_layanan === 'express'
                    ? 'Express'
                    : 'Normal';

                $rawDataPeta = $permintaan->data_peta ?? null;

                if (is_string($rawDataPeta)) {
                    $dataPeta = json_decode($rawDataPeta, true) ?: [];
                } elseif (is_array($rawDataPeta)) {
                    $dataPeta = $rawDataPeta;
                } else {
                    $dataPeta = [];
                }

                $lokasiAwalLat = $permintaan->lokasi_awal_lat
                    ?? data_get($dataPeta, 'lokasi_awal.lat');

                $lokasiAwalLng = $permintaan->lokasi_awal_lng
                    ?? data_get($dataPeta, 'lokasi_awal.lng');

                $lokasiTujuanLat = $permintaan->lokasi_tujuan_lat
                    ?? data_get($dataPeta, 'lokasi_tujuan.lat');

                $lokasiTujuanLng = $permintaan->lokasi_tujuan_lng
                    ?? data_get($dataPeta, 'lokasi_tujuan.lng');

                $estimasiJarakKm = $permintaan->estimasi_jarak_km ?? null;

                if (! is_numeric($estimasiJarakKm) || (float) $estimasiJarakKm <= 0) {
                    $estimasiJarakKm = data_get($dataPeta, 'estimasi_jarak_km')
                        ?? data_get($dataPeta, 'estimasi_jarak')
                        ?? null;
                }

                $biayaPerjalanan = (float) ($permintaan->biaya_perjalanan ?? 0);

                if ($biayaPerjalanan <= 0) {
                    $biayaPerjalanan = (float) (
                        data_get($dataPeta, 'biaya_perjalanan')
                        ?? $permintaan->biaya_layanan
                        ?? 0
                    );
                }

                $danaPembelian = (float) ($permintaan->dana_pembelian ?? 0);

                if ($danaPembelian <= 0) {
                    $danaPembelian = (float) (
                        data_get($dataPeta, 'dana_pembelian')
                        ?? 0
                    );
                }

                $catatanDanaPembelian = $permintaan->catatan_dana_pembelian
                    ?? data_get($dataPeta, 'catatan_dana_pembelian')
                    ?? null;

                $estimasiTotalBiaya = (float) ($permintaan->estimasi_total_biaya ?? 0);

                if ($estimasiTotalBiaya <= 0) {
                    $estimasiTotalBiaya = (float) (
                        data_get($dataPeta, 'estimasi_total_biaya')
                        ?? 0
                    );
                }

                if ($estimasiTotalBiaya <= 0) {
                    $estimasiTotalBiaya = $biayaPerjalanan + $danaPembelian;
                }

                $linkLokasiAwal = is_numeric($lokasiAwalLat) && is_numeric($lokasiAwalLng)
                    ? 'https://www.google.com/maps?q=' . (float) $lokasiAwalLat . ',' . (float) $lokasiAwalLng
                    : null;

                $linkLokasiTujuan = is_numeric($lokasiTujuanLat) && is_numeric($lokasiTujuanLng)
                    ? 'https://www.google.com/maps?q=' . (float) $lokasiTujuanLat . ',' . (float) $lokasiTujuanLng
                    : null;

                $linkRute = null;

                if (
                    is_numeric($lokasiAwalLat) &&
                    is_numeric($lokasiAwalLng) &&
                    is_numeric($lokasiTujuanLat) &&
                    is_numeric($lokasiTujuanLng)
                ) {
                    $origin = '-6.2728365,106.5265246';

                    $waypoint =
                        (float) $lokasiAwalLat .
                        ',' .
                        (float) $lokasiAwalLng;

                    $destination =
                        (float) $lokasiTujuanLat .
                        ',' .
                        (float) $lokasiTujuanLng;

                    $linkRute =
                        'https://www.google.com/maps/dir/?api=1' .
                        '&origin=' . $origin .
                        '&waypoints=' . $waypoint .
                        '&destination=' . $destination .
                        '&travelmode=driving';
                }

                $deskripsiMentah = (string) ($permintaan->deskripsi ?? '');

                $deskripsiBersih = trim(
                    \Illuminate\Support\Str::before(
                        $deskripsiMentah,
                        '--- Estimasi Rute & Biaya ---'
                    )
                );

                $catatanAdmin =
                    $permintaan->catatan_admin
                    ?? $permintaan->catatan
                    ?? null;

                $statusPembayaran = match ($status) {
                    'selesai' => 'selesai',

                    'diproses' => 'menunggu_pembayaran',

                    default => 'menunggu_arahan_admin',
                };

                $labelStatusPembayaran = match ($statusPembayaran) {
                    'selesai' => 'Selesai',
                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                    default => 'Menunggu Arahan Admin',
                };

                $classStatusPembayaran = match ($statusPembayaran) {
                    'selesai' => 'paid',
                    'menunggu_pembayaran' => 'waiting',
                    default => 'pending',
                };

                $nomorAdminPembayaran = preg_replace(
                    '/[^0-9]/',
                    '',
                    (string) config('services.whatsapp.admin_number', '6281385184263')
                );

                if (\Illuminate\Support\Str::startsWith($nomorAdminPembayaran, '0')) {
                    $nomorAdminPembayaran = '62' . substr($nomorAdminPembayaran, 1);
                }

                if (\Illuminate\Support\Str::startsWith($nomorAdminPembayaran, '8')) {
                    $nomorAdminPembayaran = '62' . $nomorAdminPembayaran;
                }

                $metodePembayaranList = [
                    [
                        'key' => 'DANA',
                        'nama' => 'DANA',
                        'tujuan' => '081381584263',
                        'atas_nama' => 'ESA Runner',
                        'keterangan' => 'Transfer ke nomor DANA admin.',
                    ],
                    [
                        'key' => 'GOPAY',
                        'nama' => 'GoPay',
                        'tujuan' => '081381584263',
                        'atas_nama' => 'ESA Runner',
                        'keterangan' => 'Transfer ke nomor GoPay admin.',
                    ],
                    [
                        'key' => 'BANK',
                        'nama' => 'Transfer Bank',
                        'tujuan' => 'Konfirmasi rekening ke admin',
                        'atas_nama' => 'ESA Runner',
                        'keterangan' => 'Admin akan mengirimkan nomor rekening.',
                    ],
                    [
                        'key' => 'COD',
                        'nama' => 'COD',
                        'tujuan' => 'Bayar langsung setelah disetujui admin',
                        'atas_nama' => 'ESA Runner',
                        'keterangan' => 'COD hanya berlaku setelah disetujui admin.',
                    ],
                ];

                $defaultMetodePembayaran = $metodePembayaranList[0];

                $buatLinkKonfirmasiPembayaran = function (array $metode) use (
                    $nomorAdminPembayaran,
                    $permintaan,
                    $estimasiTotalBiaya
                ) {
                    $pesan = implode(PHP_EOL, [
                        'Halo Admin, saya ingin konfirmasi pembayaran.',
                        '',
                        'Kode Request: ' . $permintaan->kode,
                        'Nama: ' . $permintaan->nama_pemesan,
                        'No HP: ' . $permintaan->no_hp,
                        'Metode Pembayaran: ' . $metode['nama'],
                        'Tujuan Pembayaran: ' . $metode['tujuan'],
                        'Atas Nama: ' . $metode['atas_nama'],
                        'Total yang perlu disiapkan: Rp ' . number_format((float) $estimasiTotalBiaya, 0, ',', '.'),
                        '',
                        $metode['key'] === 'COD'
                            ? 'Saya memilih pembayaran COD dan akan membayar langsung saat pesanan diterima.'
                            : 'Saya akan mengirimkan bukti pembayaran melalui chat ini.',
                    ]);

                    return 'https://api.whatsapp.com/send/?phone=' .
                        $nomorAdminPembayaran .
                        '&text=' .
                        urlencode($pesan);
                };

                $linkKonfirmasiPembayaran = $buatLinkKonfirmasiPembayaran($defaultMetodePembayaran);

                $pesanHubungiAdmin = implode(PHP_EOL, [
                    'Halo Admin, saya ingin menanyakan request saya.',
                    '',
                    'Kode Request: ' . $permintaan->kode,
                    'Nama: ' . $permintaan->nama_pemesan,
                    'No HP: ' . $permintaan->no_hp,
                    'Status Saat Ini: ' . $statusLabel,
                    'Total yang perlu disiapkan: Rp ' . number_format((float) $estimasiTotalBiaya, 0, ',', '.'),
                    '',
                    'Mohon arahan selanjutnya ya admin.',
                ]);

                $linkHubungiAdmin =
                    'https://api.whatsapp.com/send/?phone=' .
                    $nomorAdminPembayaran .
                    '&text=' .
                    urlencode($pesanHubungiAdmin);

                $jenisKomplainList = [
                    [
                        'label' => 'Pesanan belum diproses',
                        'deskripsi' => 'Admin atau kurir belum memproses request dalam waktu lama.',
                        'kode' => 'PESANAN_BELUM_DIPROSES',
                    ],
                    [
                        'label' => 'Admin sulit dihubungi',
                        'deskripsi' => 'User mengalami kendala saat menghubungi admin atau belum mendapat respons.',
                        'kode' => 'ADMIN_SULIT_DIHUBUNGI',
                    ],
                    [
                        'label' => 'Biaya tidak sesuai',
                        'deskripsi' => 'Nominal pembayaran atau dana pembelian terasa tidak sesuai.',
                        'kode' => 'BIAYA_TIDAK_SESUAI',
                    ],
                    [
                        'label' => 'Barang / pesanan bermasalah',
                        'deskripsi' => 'Barang tidak sesuai, rusak, kurang, atau ada kendala lain.',
                        'kode' => 'BARANG_BERMASALAH',
                    ],
                ];

                $buatLinkKomplain = function (array $komplain) use (
                    $nomorAdminPembayaran,
                    $permintaan,
                    $statusLabel,
                    $estimasiTotalBiaya
                ) {
                    $pesan = implode(PHP_EOL, [
                        'Halo Admin, saya ingin melaporkan kendala pada request saya.',
                        '',
                        'Kode Request: ' . $permintaan->kode,
                        'Nama: ' . $permintaan->nama_pemesan,
                        'No HP: ' . $permintaan->no_hp,
                        'Status Saat Ini: ' . $statusLabel,
                        'Total yang perlu disiapkan: Rp ' . number_format((float) $estimasiTotalBiaya, 0, ',', '.'),
                        '',
                        'Jenis Kendala: ' . $komplain['label'],
                        'Kode Kendala: ' . $komplain['kode'],
                        '',
                        'Catatan saya:',
                        'Tolong bantu cek kendala ini ya admin.',
                    ]);

                    return 'https://api.whatsapp.com/send/?phone=' .
                        $nomorAdminPembayaran .
                        '&text=' .
                        urlencode($pesan);
                };
            @endphp

            <div class="result-card">
                <div class="result-top">
                    <div>
                        <span class="result-label">
                            Kode Request
                        </span>

                        <h3>
                            {{ $permintaan->kode }}
                        </h3>
                    </div>

                    <div class="status-badge {{ $statusClass }}">
                        {{ $statusLabel }}
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span>Nama Pemesan</span>
                        <strong>{{ $permintaan->nama_pemesan }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Kategori</span>
                        <strong>{{ $permintaan->kategoriLayanan?->nama ?? '-' }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Tipe Layanan</span>
                        <strong>{{ $tipeLabel }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Judul</span>
                        <strong>{{ $permintaan->judul }}</strong>
                    </div>

                    <div class="info-item">
                        <span>Dibuat Pada</span>

                        <strong>
                            {{ $permintaan->created_at?->format('d M Y, H:i') ?? '-' }}
                        </strong>
                    </div>

                    <div class="info-item">
                        <span>Total yang Perlu Disiapkan</span>

                        <strong>
                            Rp {{ number_format((float) $estimasiTotalBiaya, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>

                <div class="detail-box">
                    <span>Deskripsi Permintaan</span>

                    <p>
                        {{ $deskripsiBersih ?: '-' }}
                    </p>
                </div>

                <div class="location-grid">
                    <div class="detail-box">
                        <span>Lokasi Awal / Eksekusi</span>

                        <p>
                            {{ $permintaan->lokasi_awal ?: '-' }}
                        </p>
                    </div>

                    <div class="detail-box">
                        <span>Lokasi Tujuan</span>

                        <p>
                            {{ $permintaan->lokasi_tujuan ?: '-' }}
                        </p>
                    </div>
                </div>

                <div class="info-grid" style="margin-top: 14px;">
                    <div class="info-item">
                        <span>Estimasi Jarak</span>

                        <strong>
                            @if ($estimasiJarakKm)
                                {{ number_format((float) $estimasiJarakKm, 2, ',', '.') }} KM
                            @else
                                Belum tersedia
                            @endif
                        </strong>
                    </div>

                    <div class="info-item">
                        <span>Biaya Perjalanan</span>

                        <strong>
                            Rp {{ number_format((float) $biayaPerjalanan, 0, ',', '.') }}
                        </strong>
                    </div>

                    @if ((float) $danaPembelian > 0)
                        <div class="info-item">
                            <span>Dana Pembelian</span>

                            <strong>
                                Rp {{ number_format((float) $danaPembelian, 0, ',', '.') }}
                            </strong>
                        </div>
                    @endif

                    <div class="info-item">
                        <span>Total yang Perlu Disiapkan</span>

                        <strong>
                            Rp {{ number_format((float) $estimasiTotalBiaya, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="info-item">
                        <span>Rute</span>

                        <strong>
                            Basecamp → Awal → Tujuan
                        </strong>
                    </div>
                </div>

                @if ((float) $danaPembelian > 0 && filled($catatanDanaPembelian))
                    <div class="detail-box">
                        <span>Catatan Dana Pembelian</span>

                        <p>
                            {{ $catatanDanaPembelian }}
                        </p>
                    </div>
                @endif

                <div class="payment-card">
                    <div class="payment-top">
                        <div>
                            <span>Pembayaran</span>
                            <h4>Status Pembayaran</h4>
                        </div>

                        <div class="payment-badge {{ $classStatusPembayaran }}">
                            {{ $labelStatusPembayaran }}
                        </div>
                    </div>

                    <div class="payment-grid">
                        <div class="payment-item">
                            <span>Total yang Perlu Disiapkan</span>

                            <strong>
                                Rp {{ number_format((float) $estimasiTotalBiaya, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="payment-item">
                            <span>Alur Pembayaran</span>

                            <strong>
                                Melalui arahan admin
                            </strong>
                        </div>
                    </div>

                    @if ($statusPembayaran === 'menunggu_pembayaran')
                        <span class="payment-method-title">
                            Pilih Metode Pembayaran
                        </span>

                        <div class="payment-method-grid">
                            @foreach ($metodePembayaranList as $index => $metode)
                                <label class="payment-method-option">
                                    <input
                                        type="radio"
                                        name="metode_pembayaran_{{ $permintaan->id }}"
                                        value="{{ $metode['key'] }}"
                                        data-payment-link="{{ $buatLinkKonfirmasiPembayaran($metode) }}"
                                        @checked($index === 0)
                                    >

                                    <div class="payment-method-box">
                                        <strong>{{ $metode['nama'] }}</strong>

                                        <span>
                                            {{ $metode['keterangan'] }}
                                        </span>

                                        <small>
                                            {{ $metode['tujuan'] }}
                                        </small>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <p class="payment-note">
                            Silakan pilih metode pembayaran, lakukan pembayaran sesuai arahan admin,
                            lalu kirim bukti pembayaran melalui WhatsApp. Untuk COD, pembayaran
                            hanya berlaku setelah disetujui admin.
                        </p>

                        <div class="payment-actions">
                            <a
                                href="{{ $linkKonfirmasiPembayaran }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="payment-whatsapp-btn"
                                id="paymentConfirmButton-{{ $permintaan->id }}"
                            >
                                Konfirmasi Pembayaran via WhatsApp
                            </a>
                        </div>
                    @elseif ($statusPembayaran === 'selesai')
                        <p class="payment-note">
                            Request sudah selesai. Pembayaran dan proses layanan dianggap sudah ditangani oleh admin.
                        </p>

                        <div class="payment-actions">
                            <a
                                href="{{ $linkHubungiAdmin }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="payment-whatsapp-btn"
                            >
                                Hubungi Admin via WhatsApp
                            </a>
                        </div>
                    @else
                        <p class="payment-note">
                            Request kamu sedang menunggu arahan admin. Pembayaran dilakukan setelah
                            admin mengonfirmasi request melalui WhatsApp. Nominal dapat berubah jika
                            ada penyesuaian dana pembelian, biaya layanan, atau kondisi pesanan.
                        </p>

                        <div class="payment-actions">
                            <a
                                href="{{ $linkHubungiAdmin }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="payment-whatsapp-btn"
                            >
                                Hubungi Admin via WhatsApp
                            </a>
                        </div>
                    @endif
                </div>

                @if (! in_array($status, ['selesai', 'dibatalkan', 'ditolak']))
                    <div class="complaint-card">
                        <div class="complaint-top">
                            <div>
                                <span>Lapor Kendala</span>
                                <h4>Ada masalah dengan request?</h4>
                            </div>

                            <div class="complaint-badge">
                                Bantuan Admin
                            </div>
                        </div>

                        <p class="complaint-note">
                            Pilih jenis kendala yang paling sesuai. Sistem akan membuka WhatsApp
                            admin dengan format laporan otomatis berdasarkan request kamu.
                        </p>

                        <div class="complaint-grid">
                            @foreach ($jenisKomplainList as $komplain)
                                <a
                                    href="{{ $buatLinkKomplain($komplain) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="complaint-option"
                                >
                                    <strong>
                                        {{ $komplain['label'] }}
                                    </strong>

                                    <span>
                                        {{ $komplain['deskripsi'] }}
                                    </span>

                                    <small>
                                        Laporkan via WhatsApp
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($linkRute || $linkLokasiAwal || $linkLokasiTujuan)
                    <div class="map-grid">
                        @if ($linkRute)
                            <a
                                href="{{ $linkRute }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="map-link"
                            >
                                📍 Buka Rute
                            </a>
                        @endif

                        @if ($linkLokasiAwal)
                            <a
                                href="{{ $linkLokasiAwal }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="map-link"
                            >
                                Titik Awal
                            </a>
                        @endif

                        @if ($linkLokasiTujuan)
                            <a
                                href="{{ $linkLokasiTujuan }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="map-link"
                            >
                                Titik Tujuan
                            </a>
                        @endif
                    </div>
                @endif

                @if ($catatanAdmin)
                    <div class="admin-note">
                        <span>Catatan Admin</span>

                        <p>
                            {{ $catatanAdmin }}
                        </p>
                    </div>
                @endif

                <div class="timeline-card">
                    <span>Riwayat Status</span>

                    <div class="timeline-list">
                        @forelse ($riwayatStatus as $log)
                            <div class="timeline-item">
                                <div class="timeline-dot">
                                    ✓
                                </div>

                                <div class="timeline-content">
                                    <strong>
                                        {{
                                            \Illuminate\Support\Str::headline(
                                                $log->status_baru ?? '-'
                                            )
                                        }}
                                    </strong>

                                    <p>
                                        {{ $log->catatan ?: 'Status request diperbarui.' }}
                                    </p>

                                    <time>
                                        {{ $log->created_at?->format('d M Y, H:i') ?? '-' }}
                                    </time>
                                </div>
                            </div>
                        @empty
                            <div class="timeline-item">
                                <div class="timeline-dot">
                                    ✓
                                </div>

                                <div class="timeline-content">
                                    <strong>
                                        {{ $statusLabel }}
                                    </strong>

                                    <p>
                                        Request sudah tercatat di sistem.
                                    </p>

                                    <time>
                                        {{ $permintaan->created_at?->format('d M Y, H:i') ?? '-' }}
                                    </time>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if ($pesanRefresh)
                    <div class="refresh-message">
                        {{ $pesanRefresh }}
                    </div>
                @endif

                <div class="result-actions">
                    <button
                        type="button"
                        class="refresh-btn"
                        wire:click="refreshStatus"
                        wire:loading.attr="disabled"
                    >
                        Refresh Status
                    </button>

                    <button
                        type="button"
                        class="reset-btn"
                        wire:click="resetPencarian"
                    >
                        Cek Request Lain
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('change', function (event) {
        const input = event.target.closest('input[name^="metode_pembayaran_"]');

        if (!input) {
            return;
        }

        const paymentCard = input.closest('.payment-card');

        if (!paymentCard) {
            return;
        }

        const button = paymentCard.querySelector('.payment-whatsapp-btn');
        const link = input.getAttribute('data-payment-link');

        if (button && link) {
            button.setAttribute('href', link);
        }
    });
</script>