<x-filament-panels::page>
    @php
        $laporan = $this->getDataLaporan();
    @endphp

    <x-filament::section>
        <x-slot name="heading">
            Filter Periode Laporan
        </x-slot>

        {{ $this->form }}
    </x-filament::section>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Total Pesanan
            </p>

            <p class="mt-2 text-2xl font-bold text-gray-950 dark:text-white">
                {{ number_format($laporan['total_pesanan'], 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pesanan Aktif
            </p>

            <p class="mt-2 text-2xl font-bold text-warning-600">
                {{ number_format($laporan['pesanan_aktif'], 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pesanan Selesai
            </p>

            <p class="mt-2 text-2xl font-bold text-success-600">
                {{ number_format($laporan['pesanan_selesai'], 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pesanan Dibatalkan
            </p>

            <p class="mt-2 text-2xl font-bold text-danger-600">
                {{ number_format($laporan['pesanan_dibatalkan'], 0, ',', '.') }}
            </p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pendapatan Jasa
            </p>

            <p class="mt-2 text-2xl font-bold text-primary-600">
                Rp{{ number_format(
                    $laporan['pendapatan_jasa'],
                    0,
                    ',',
                    '.'
                ) }}
            </p>
        </div>
    </div>

    <x-filament::section>
        <x-slot name="heading">
            Detail Pesanan
        </x-slot>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-left text-sm dark:divide-white/10">
                <thead>
                    <tr class="bg-gray-50 dark:bg-white/5">
                        <th class="whitespace-nowrap px-4 py-3 font-semibold">
                            Kode Order
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 font-semibold">
                            Tanggal
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 font-semibold">
                            Pelanggan
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 font-semibold">
                            Layanan
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 font-semibold">
                            Kurir
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 font-semibold">
                            Status
                        </th>

                        <th class="whitespace-nowrap px-4 py-3 text-right font-semibold">
                            Total Biaya
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                    @forelse ($laporan['pesanan'] as $pesanan)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 font-medium">
                                {{ $pesanan->kode_order }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                {{ $pesanan->tanggal_order?->format('d M Y H:i') ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="font-medium">
                                    {{ $pesanan->nama_pelanggan }}
                                </div>

                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $pesanan->nomor_whatsapp }}
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                {{ $pesanan->jenisLayanan?->nama_layanan ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $pesanan->kurir?->name ?? 'Belum ditugaskan' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-3">
                                <x-filament::badge
                                    :color="match ($pesanan->status_order) {
                                        'menunggu_verifikasi' => 'gray',
                                        'menunggu_dana_titip' => 'warning',
                                        'menunggu_kurir' => 'info',
                                        'dalam_perjalanan' => 'primary',
                                        'selesai' => 'success',
                                        'dibatalkan' => 'danger',
                                        default => 'gray',
                                    }"
                                >
                                    {{ \App\Models\Order::labelStatus(
                                        $pesanan->status_order
                                    ) }}
                                </x-filament::badge>
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 text-right font-medium">
                                Rp{{ number_format(
                                    $pesanan->total_biaya_jasa,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="7"
                                class="px-4 py-10 text-center text-gray-500 dark:text-gray-400"
                            >
                                Belum ada pesanan pada periode yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>