<?php

use Bnussbau\TrmnlBlade\View\Components\Chart;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;

it('renders chart container with id and default class', function () {
    $chart = new Chart;
    $rendered = $chart->render();
    $html = $rendered->with([
        'attributes' => new ComponentAttributeBag([]),
        'id' => 'traffic-chart',
    ])->render();

    expect($html)->toContain('id="traffic-chart"');
    expect($html)->toContain('class="w--full"');
});

it('injects Highcharts tags above the chart container', function () {
    $chart = new Chart;
    $rendered = $chart->render();
    $html = $rendered->with([
        'attributes' => new ComponentAttributeBag([]),
        'id' => 'traffic-chart',
    ])->render();

    expect($html)->toContain('https://trmnl.com/js/highcharts/12.3.0/highcharts.js');
    expect($html)->toContain('https://trmnl.com/js/highcharts/12.3.0/pattern-fill.js');
    expect(strpos($html, 'highcharts.js'))->toBeLessThan(strpos($html, 'id="traffic-chart"'));
    expect(strpos($html, 'pattern-fill.js'))->toBeLessThan(strpos($html, 'id="traffic-chart"'));
});

it('merges extra classes onto the chart container', function () {
    $chart = new Chart;
    $rendered = $chart->render();
    $html = $rendered->with([
        'attributes' => new ComponentAttributeBag(['class' => 'h--48']),
        'id' => 'sales-chart',
    ])->render();

    expect($html)->toContain('w--full');
    expect($html)->toContain('h--48');
    expect($html)->toContain('id="sales-chart"');
});

it('emits Highcharts tags once for multiple charts', function () {
    $html = Blade::render('<x-trmnl::chart id="a" /><x-trmnl::chart id="b" />');

    expect(substr_count($html, 'highcharts.js'))->toBe(1);
    expect(substr_count($html, 'pattern-fill.js'))->toBe(1);
    expect($html)->toContain('id="a"');
    expect($html)->toContain('id="b"');
});

it('emits whenReady helper without auto-init', function () {
    $chart = new Chart;
    $rendered = $chart->render();
    $html = $rendered->with([
        'attributes' => new ComponentAttributeBag([]),
        'id' => 'traffic-chart',
    ])->render();

    expect($html)->toContain('window.trmnlChartsWhenReady');
    expect($html)->not->toContain('Highcharts.chart');
    expect($html)->not->toContain('TRMNLCharts.watch');
});

it('uses configured Highcharts URLs', function () {
    config()->set('trmnl-blade.highcharts_js_url', 'https://cdn.example.com/highcharts.js');
    config()->set('trmnl-blade.highcharts_pattern_fill_url', 'https://cdn.example.com/pattern-fill.js');

    $html = Blade::render('<x-trmnl::chart id="traffic-chart" />');

    expect($html)->toContain('https://cdn.example.com/highcharts.js');
    expect($html)->toContain('https://cdn.example.com/pattern-fill.js');
});
