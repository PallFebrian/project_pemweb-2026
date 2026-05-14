@php
    use App\Models\PermintaanLayanan;

    $jumlahOrderBaru = PermintaanLayanan::query()
        ->where('status', 'baru')
        ->whereNull('dibaca_admin_pada')
        ->count();

    $url = route('admin.notifikasi-order.buka');
@endphp

<a href="{{ $url }}"
   title="Orderan baru"
   style="
        position: relative;
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        margin-right: 10px;
        margin-left: 4px;
        color: #374151;
        background: {{ $jumlahOrderBaru > 0 ? '#fff1f2' : 'transparent' }};
        border: {{ $jumlahOrderBaru > 0 ? '1px solid #fecdd3' : '1px solid transparent' }};
        transition: all .2s ease;
   "
>
    <svg xmlns="http://www.w3.org/2000/svg"
         width="22"
         height="22"
         viewBox="0 0 24 24"
         fill="none"
         stroke="{{ $jumlahOrderBaru > 0 ? '#ef4444' : '#6b7280' }}"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">
        <path d="M10.268 21a2 2 0 0 0 3.464 0" />
        <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
    </svg>

    @if ($jumlahOrderBaru > 0)
        <span style="
            position: absolute;
            top: 3px;
            right: 2px;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 800;
            border: 2px solid white;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, .2);
        ">
            {{ $jumlahOrderBaru > 99 ? '99+' : $jumlahOrderBaru }}
        </span>
    @endif
</a>