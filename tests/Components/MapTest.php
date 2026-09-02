<?php

use Bnussbau\TrmnlBlade\View\Components\Map;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\ComponentSlot;

it('renders map container with id and default classes', function () {
    $map = new Map;
    $rendered = $map->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag([]),
        'id' => 'map-streets',
    ])->render();

    expect($html)->toContain('id="map-streets"');
    expect($html)->toContain('class="map stretch w--full rounded--base"');
});

it('injects MapLibre tags above the map container', function () {
    $map = new Map;
    $rendered = $map->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag([]),
        'id' => 'map-streets',
    ])->render();

    expect($html)->toContain('https://trmnl.com/js/maplibre-gl/5.24.0/maplibre-gl.js');
    expect($html)->toContain('https://trmnl.com/js/maplibre-gl/5.24.0/maplibre-gl.css');
    expect(strpos($html, 'maplibre-gl.js'))->toBeLessThan(strpos($html, 'id="map-streets"'));
});

it('merges extra classes onto the map container', function () {
    $map = new Map;
    $rendered = $map->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag(['class' => 'col--span-3']),
        'id' => 'map-strava',
    ])->render();

    expect($html)->toContain('map stretch w--full rounded--base');
    expect($html)->toContain('col--span-3');
    expect($html)->toContain('id="map-strava"');
});

it('renders overlay slot inside the map container', function () {
    $map = new Map;
    $rendered = $map->render();
    $html = $rendered->with([
        'slot' => new ComponentSlot('<div class="absolute">card</div>'),
        'attributes' => new ComponentAttributeBag([]),
        'id' => 'map-overlay',
    ])->render();

    expect($html)->toContain('<div class="absolute">card</div>');
    expect($html)->toMatch('/id="map-overlay"[^>]*>.*<div class="absolute">card<\/div>/s');
});

it('emits auto-init script when center and zoom are set', function () {
    $map = new Map;
    $rendered = $map->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag([]),
        'id' => 'map-streets',
        'preset' => 'minimal',
        'center' => [-84.3885, 33.7554],
        'zoom' => 13,
        'marker' => false,
    ])->render();

    expect($html)->toContain('window.trmnlMapsWhenReady');
    expect($html)->toContain('TRMNLMaps.watch');
    expect($html)->toContain('preset: "minimal"');
    expect($html)->toContain('center: [-84.3885,33.7554]');
    expect($html)->toContain('zoom: 13');
    expect($html)->not->toContain('TRMNLMaps.dot');
});

it('skips auto-init when center and zoom are omitted', function () {
    $map = new Map;
    $rendered = $map->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag([]),
        'id' => 'map-walk',
    ])->render();

    expect($html)->toContain('id="map-walk"');
    expect($html)->not->toContain('TRMNLMaps.watch');
    expect($html)->not->toContain('window.trmnlMapsWhenReady');
});

it('draws a center marker when marker is true', function () {
    $map = new Map;
    $rendered = $map->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag([]),
        'id' => 'map-overlay',
        'preset' => 'streets',
        'center' => [-84.4008, 33.7554],
        'zoom' => 15,
        'marker' => true,
    ])->render();

    expect($html)->toContain('TRMNLMaps.dot');
    expect($html)->toContain('map.on("load"');
});

it('emits MapLibre tags once for multiple maps', function () {
    $html = Blade::render('<x-trmnl::map id="a" /><x-trmnl::map id="b" />');

    expect(substr_count($html, 'maplibre-gl.js'))->toBe(1);
    expect(substr_count($html, 'maplibre-gl.css'))->toBe(1);
    expect($html)->toContain('id="a"');
    expect($html)->toContain('id="b"');
});

it('uses configured MapLibre URLs', function () {
    config()->set('trmnl-blade.maplibre_js_url', 'https://cdn.example.com/maplibre.js');
    config()->set('trmnl-blade.maplibre_css_url', 'https://cdn.example.com/maplibre.css');

    $html = Blade::render('<x-trmnl::map id="map-streets" />');

    expect($html)->toContain('https://cdn.example.com/maplibre.js');
    expect($html)->toContain('https://cdn.example.com/maplibre.css');
});
