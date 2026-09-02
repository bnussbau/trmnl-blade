@props([
    'id',
])

@once('trmnl-highcharts')
    <script src="{{ config('trmnl-blade.highcharts_js_url') }}"></script>
    <script src="{{ config('trmnl-blade.highcharts_pattern_fill_url') }}"></script>
@endonce

<div {{ $attributes->merge(['id' => $id, 'class' => 'w--full']) }}></div>

@once('trmnl-charts-when-ready')
    <script>
        window.trmnlChartsWhenReady =
            window.trmnlChartsWhenReady ||
            function (cb) {
                var tries = 0;
                (function attempt() {
                    if (window.TRMNLCharts && window.Highcharts) return cb();
                    if (++tries > 200) return;
                    setTimeout(attempt, 50);
                })();
            };
    </script>
@endonce
