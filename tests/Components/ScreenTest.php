<?php

use Bnussbau\TrmnlBlade\View\Components\Screen;

it('uses same framework version for both CSS and JS when only framework_version is set', function () {
    config()->set('trmnl-blade.framework_version', '2.1.0');
    config()->set('trmnl-blade.framework_css_version', null);
    config()->set('trmnl-blade.framework_js_version', null);
    config()->set('trmnl-blade.framework_css_url', null);
    config()->set('trmnl-blade.framework_js_url', null);

    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with('slot', '')->render();

    expect($html)->toContain('https://trmnl.com/css/2.1.0/plugins.css');
    expect($html)->toContain('https://trmnl.com/js/2.1.0/plugins.js');
});

it('uses framework_css_version for CSS link when set', function () {
    config()->set('trmnl-blade.framework_version', '2.1.0');
    config()->set('trmnl-blade.framework_css_version', '2.2.1');
    config()->set('trmnl-blade.framework_js_version', null);
    config()->set('trmnl-blade.framework_css_url', null);
    config()->set('trmnl-blade.framework_js_url', null);

    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with('slot', '')->render();

    expect($html)->toContain('https://trmnl.com/css/2.2.1/plugins.css');
    expect($html)->toContain('https://trmnl.com/js/2.1.0/plugins.js');
});

it('uses framework_js_version for script src when set', function () {
    config()->set('trmnl-blade.framework_version', '2.1.0');
    config()->set('trmnl-blade.framework_css_version', null);
    config()->set('trmnl-blade.framework_js_version', '2.0.0');
    config()->set('trmnl-blade.framework_css_url', null);
    config()->set('trmnl-blade.framework_js_url', null);

    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with('slot', '')->render();

    expect($html)->toContain('https://trmnl.com/css/2.1.0/plugins.css');
    expect($html)->toContain('https://trmnl.com/js/2.0.0/plugins.js');
});

it('uses both framework_css_version and framework_js_version when both set', function () {
    config()->set('trmnl-blade.framework_version', '2.1.0');
    config()->set('trmnl-blade.framework_css_version', '2.2.1');
    config()->set('trmnl-blade.framework_js_version', '2.1.0');
    config()->set('trmnl-blade.framework_css_url', null);
    config()->set('trmnl-blade.framework_js_url', null);

    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with('slot', '')->render();

    expect($html)->toContain('https://trmnl.com/css/2.2.1/plugins.css');
    expect($html)->toContain('https://trmnl.com/js/2.1.0/plugins.js');
});

it('renders screen component with default noBleed value', function () {
    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with('slot', 'Test content')->render();

    expect($html)->toContain('<div class="screen');
    expect($html)->toContain('Test content');
});

it('renders screen component with noBleed set to true', function () {
    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with([
        'slot' => 'Test content',
        'noBleed' => true,
    ])->render();

    expect($html)->toContain('<div class="screen screen--no-bleed');
    expect($html)->toContain('Test content');
});

it('renders screen component with darkMode set to true', function () {
    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with([
        'slot' => 'Test content',
        'darkMode' => true,
    ])->render();

    expect($html)->toContain('<div class="screen  screen--dark-mode');
    expect($html)->toContain('Test content');
});

it('renders screen component with theme set to dark', function () {
    config()->set('trmnl-blade.framework_version', '3.2.0');
    config()->set('trmnl-blade.framework_css_version', null);
    config()->set('trmnl-blade.framework_css_url', null);
    config()->set('trmnl-blade.theme_urls', []);

    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with([
        'slot' => 'Test content',
        'theme' => 'dark',
    ])->render();

    expect($html)->toContain('https://trmnl.com/css/3.2.0/plugins.css');
    expect($html)->toContain('https://trmnl.com/css/3.2.0/themes/dark-theme.css');
    expect($html)->toContain('screen--theme-dark');
    expect($html)->toContain('Test content');
});

it('does not render theme link or class when theme is null', function () {
    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with([
        'slot' => 'Test content',
        'theme' => null,
    ])->render();

    expect($html)->not->toContain('themes/');
    expect($html)->not->toContain('screen--theme-');
});

it('uses custom theme URL from config', function () {
    config()->set('trmnl-blade.theme_urls', [
        'dark' => 'https://cdn.example.com/custom-dark-theme.css',
    ]);

    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with([
        'slot' => 'Test content',
        'theme' => 'dark',
    ])->render();

    expect($html)->toContain('https://cdn.example.com/custom-dark-theme.css');
    expect($html)->toContain('screen--theme-dark');
});

it('renders screen component with noBleed set to false', function () {
    $screen = new Screen;
    $rendered = $screen->render();
    $html = $rendered->with([
        'slot' => 'Test content',
        'noBleed' => false,
    ])->render();

    expect($html)->toContain('<div class="screen');
    expect($html)->toContain('Test content');
});
