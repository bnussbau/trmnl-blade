# TRMNL Blade - Blade Components for the TRMNL Design System

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bnussbau/laravel-trmnl-blade.svg?style=flat-square)](https://packagist.org/packages/bnussbau/trmnl-blade)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bnussbau/trmnl-blade/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bnussbau/trmnl-blade/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/bnussbau/trmnl-blade/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/bnussbau/trmnl-blade/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bnussbau/laravel-trmnl-blade.svg?style=flat-square)](https://packagist.org/packages/bnussbau/trmnl-blade)

🎨 Blade Components on top of the TRMNL Design System ([docs](https://trmnl.com/framework))

## Support us

## Blade Components
- [see TRMNL Design System](https://trmnl.com/framework)
- [resources/views/components](resources/views/components)

Blade Compontens can help you generate markup code. Alternatively, you can just use the native CSS classes from the TRMNL Design System.

### Usage

### Basic Layout

```blade
<x-trmnl::screen>
    <x-trmnl::view>
        <x-trmnl::layout>
            <!-- Your content here -->
        </x-trmnl::layout>
        <x-trmnl::title-bar/>
    </x-trmnl::view>
</x-trmnl::screen>
```

### Quote Example

```blade
<x-trmnl::screen>
    <x-trmnl::view>
        <x-trmnl::layout>
            <x-trmnl::richtext gapSize="large" align="center">
                <x-trmnl::title>Motivational Quote</x-trmnl::title>
                <x-trmnl::content>“I love inside jokes. I hope to be a part of one someday.”</x-trmnl::content>
                <x-trmnl::label variant="underline">Michael Scott</x-trmnl::label>
            </x-trmnl::richtext>
        </x-trmnl::layout>
        <x-trmnl::title-bar/>
    </x-trmnl::view>
</x-trmnl::screen>
```

### Map Example

```blade
<x-trmnl::screen>
    <x-trmnl::view>
        <x-trmnl::layout direction="col">
            <x-trmnl::map id="map-streets" preset="streets" :center="[-84.3885, 33.7554]" :zoom="13" />
        </x-trmnl::layout>
        <x-trmnl::title-bar title="Map" instance="Streets"/>
    </x-trmnl::view>
</x-trmnl::screen>
```

Using `<x-trmnl::map>` injects MapLibre GL JS once above the map. Pass `center` (`[lng, lat]`) and `zoom` to auto-init a still place-map. Omit them to supply your own `TRMNLMaps` JS for routes. Overlay cards go in the slot; `marker` draws a dot at `center`.

### Chart Example

```blade
<x-trmnl::screen>
    <x-trmnl::view>
        <x-trmnl::layout direction="col">
            <x-trmnl::chart id="traffic-chart" />
        </x-trmnl::layout>
        <x-trmnl::title-bar title="Analytics" instance="Traffic"/>
    </x-trmnl::view>
</x-trmnl::screen>

<script>
window.trmnlChartsWhenReady(function () {
  var el = "traffic-chart";
  TRMNLCharts.watch(el, function () {
    var px = function (value) { return TRMNLPaint.px(value, { el: el }); };
    var chart = Highcharts.chart(el, TRMNLCharts.merge(TRMNLCharts.options({ el: el }), {
      chart: { type: "spline", height: px(260) },
      series: [{
        data: @json($series),
        color: TRMNLCharts.series(0, 1, { el: el }),
        lineWidth: px(4)
      }]
    }));
    TRMNLCharts.applySwatches({ el: el });
    return chart;
  });
});
</script>
```

Using `<x-trmnl::chart>` injects Highcharts and pattern-fill once above the container. Write your own `TRMNLCharts` JS (`watch`, `options`, `series`). Do not wrap Highcharts options as Blade props. Gauges and Chartkick stay in your own `<script>` tags.

## Installation

You can install the package via composer:

```bash
composer require bnussbau/trmnl-blade
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="trmnl-blade-config"
```

This is the contents of the published config file:

```php
return [
    'framework_version' => env('TRMNL_BLADE_FRAMEWORK_VERSION', '3.3.1'),
    'framework_css_version' => env('TRMNL_BLADE_FRAMEWORK_CSS_VERSION', null),
    'framework_js_version' => env('TRMNL_BLADE_FRAMEWORK_JS_VERSION', null),
    'framework_css_url' => env('TRMNL_BLADE_FRAMEWORK_CSS_URL', null),
    'framework_js_url' => env('TRMNL_BLADE_FRAMEWORK_JS_URL', null),
    'maplibre_js_url' => env('TRMNL_BLADE_MAPLIBRE_JS_URL', 'https://trmnl.com/js/maplibre-gl/5.24.0/maplibre-gl.js'),
    'maplibre_css_url' => env('TRMNL_BLADE_MAPLIBRE_CSS_URL', 'https://trmnl.com/js/maplibre-gl/5.24.0/maplibre-gl.css'),
    'highcharts_js_url' => env('TRMNL_BLADE_HIGHCHARTS_JS_URL', 'https://trmnl.com/js/highcharts/12.3.0/highcharts.js'),
    'highcharts_pattern_fill_url' => env('TRMNL_BLADE_HIGHCHARTS_PATTERN_FILL_URL', 'https://trmnl.com/js/highcharts/12.3.0/pattern-fill.js'),
    'themes' => ['black-and-yellow', 'dark', 'white-and-red'],
    'theme_urls' => [], // override a theme's default url
];
```

**Optionally**, you can publish the views using

```bash
php artisan vendor:publish --tag="trmnl-views"
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Benjamin Nussbaum](https://github.com/bnussbau)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
