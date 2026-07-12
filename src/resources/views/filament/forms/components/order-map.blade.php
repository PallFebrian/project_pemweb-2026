@php
    $dataPeta = is_array($getState())
        ? $getState()
        : [];

    $mapId = 'order-map-'
        . md5(json_encode($dataPeta));
@endphp

@once
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    ></script>
@endonce

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @if (
        ! empty($dataPeta['route'])
        && ! empty($dataPeta['points'])
    )
        <div
            x-data
            x-init="
                const initMap = () => {
                    if (typeof L === 'undefined') {
                        setTimeout(initMap, 100);
                        return;
                    }

                    const element = document.getElementById(
                        '{{ $mapId }}'
                    );

                    if (
                        ! element
                        || element.dataset.ready === '1'
                    ) {
                        return;
                    }

                    element.dataset.ready = '1';

                    const dataPeta = @js($dataPeta);

                    const map = L.map(element, {
                        scrollWheelZoom: false
                    });

                    L.tileLayer(
                        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                        {
                            maxZoom: 19,
                            attribution:
                                '&copy; OpenStreetMap contributors'
                        }
                    ).addTo(map);

                    const route = L.polyline(
                        dataPeta.route,
                        {
                            color: '#2563eb',
                            weight: 5,
                            opacity: 0.85
                        }
                    ).addTo(map);

                    const points = dataPeta.points;

                    L.marker([
                        points.basecamp.lat,
                        points.basecamp.lng
                    ])
                        .addTo(map)
                        .bindPopup('<strong>Basecamp</strong>');

                    L.marker([
                        points.eksekusi.lat,
                        points.eksekusi.lng
                    ])
                        .addTo(map)
                        .bindPopup(
                            '<strong>Lokasi Eksekusi</strong>'
                        );

                    L.marker([
                        points.tujuan.lat,
                        points.tujuan.lng
                    ])
                        .addTo(map)
                        .bindPopup(
                            '<strong>Lokasi Tujuan</strong>'
                        );

                    map.fitBounds(
                        route.getBounds(),
                        {
                            padding: [30, 30]
                        }
                    );

                    setTimeout(
                        () => map.invalidateSize(),
                        300
                    );
                };

                initMap();
            "
        >
            <div
                id="{{ $mapId }}"
                wire:ignore
                style="
                    width: 100%;
                    height: 420px;
                    border: 1px solid #d1d5db;
                    border-radius: 12px;
                    overflow: hidden;
                    z-index: 1;
                "
            ></div>

            <div
                style="
                    margin-top: 12px;
                    font-size: 14px;
                "
            >
                Total estimasi jarak:
                <strong>
                    {{ number_format(
                        (float) (
                            $dataPeta['distance_km'] ?? 0
                        ),
                        2,
                        ',',
                        '.'
                    ) }}
                    KM
                </strong>
            </div>
        </div>
    @else
        <div
            style="
                padding: 24px;
                text-align: center;
                border: 1px dashed #9ca3af;
                border-radius: 12px;
            "
        >
            Peta belum tersedia. Klik
            <strong>Hitung Jarak Otomatis</strong>
            terlebih dahulu.
        </div>
    @endif
</x-dynamic-component>