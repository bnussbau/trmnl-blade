@props([
    'id',
    'preset' => 'streets',
    'center' => null,
    'zoom' => null,
    'marker' => false,
])

@php
    $autoInit = $center !== null && $center !== [] && $zoom !== null && $zoom !== '';
@endphp

@once('trmnl-maplibre')
    <script src="{{ config('trmnl-blade.maplibre_js_url') }}"></script>
    <link href="{{ config('trmnl-blade.maplibre_css_url') }}" rel="stylesheet" />
@endonce

<div {{ $attributes->merge(['id' => $id, 'class' => 'map stretch w--full rounded--base']) }}>{{ $slot }}</div>

@if ($autoInit)
    @once('trmnl-maps-when-ready')
        <script>
            window.trmnlMapsWhenReady =
                window.trmnlMapsWhenReady ||
                function (cb) {
                    var tries = 0;
                    (function attempt() {
                        if (window.TRMNLMaps && window.maplibregl) return cb();
                        if (++tries > 200) return;
                        setTimeout(attempt, 50);
                    })();
                };
        </script>
    @endonce
    <script>
        window.trmnlMapsWhenReady(function () {
            var el = @json($id);
            TRMNLMaps.watch(el, function () {
                var map = new maplibregl.Map(
                    TRMNLMaps.options({
                        el: el,
                        preset: @json($preset),
                        center: @json($center),
                        zoom: @json($zoom),
                    }),
                );
                @if ($marker)
                    map.on("load", function () {
                        TRMNLMaps.dot(map, @json($center), { el: el });
                    });
                @endif
                return map;
            });
        });
    </script>
@endif
