<?php

// config for Bnussbau/TrmnlBlade
return [
    'framework_version' => env('TRMNL_BLADE_FRAMEWORK_VERSION', '3.3.1'),
    'framework_css_version' => env('TRMNL_BLADE_FRAMEWORK_CSS_VERSION', null),
    'framework_js_version' => env('TRMNL_BLADE_FRAMEWORK_JS_VERSION', null),
    'framework_css_url' => env('TRMNL_BLADE_FRAMEWORK_CSS_URL', null),
    'framework_js_url' => env('TRMNL_BLADE_FRAMEWORK_JS_URL', null),
    'maplibre_js_url' => env('TRMNL_BLADE_MAPLIBRE_JS_URL', 'https://trmnl.com/js/maplibre-gl/5.24.0/maplibre-gl.js'),
    'maplibre_css_url' => env('TRMNL_BLADE_MAPLIBRE_CSS_URL', 'https://trmnl.com/js/maplibre-gl/5.24.0/maplibre-gl.css'),
    'themes' => [
        'black-and-yellow',
        'dark',
        'white-and-red',
    ],
    'theme_urls' => [
        // override a theme's default
        // 'dark' => 'https://example.com/themes/dark-theme.css',
    ],
];
