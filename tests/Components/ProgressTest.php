<?php

use Bnussbau\TrmnlBlade\View\Components\Progress;
use Illuminate\View\ComponentAttributeBag;

it('renders progress component with default class', function () {
    $progress = new Progress;
    $rendered = $progress->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag([]),
    ])->render();

    expect($html)->toContain('<div class="progress-bar">');
});

it('renders progress component with size small', function () {
    $progress = new Progress;
    $rendered = $progress->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag(['size' => 'small']),
    ])->render();

    expect($html)->toContain('<div class="progress-bar progress-bar--small">');
});

it('merges extra classes and attributes with size', function () {
    $progress = new Progress;
    $rendered = $progress->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag([
            'size' => 'small',
            'class' => 'progress-bar--emphasis-2',
            'data-pixel-perfect' => 'true',
        ]),
    ])->render();

    expect($html)->toContain('progress-bar progress-bar--small');
    expect($html)->toContain('progress-bar--emphasis-2');
    expect($html)->toContain('data-pixel-perfect="true"');
});

it('renders progress dots variant with root class', function () {
    $progress = new Progress;
    $rendered = $progress->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag(['variant' => 'dots']),
    ])->render();

    expect($html)->toContain('<div class="progress-dots">');
});

it('renders progress dots variant with size', function () {
    $progress = new Progress;
    $rendered = $progress->render();
    $html = $rendered->with([
        'slot' => '',
        'attributes' => new ComponentAttributeBag([
            'variant' => 'dots',
            'size' => 'small',
        ]),
    ])->render();

    expect($html)->toContain('<div class="progress-dots progress-dots--small">');
});
