<?php

use Bnussbau\TrmnlBlade\Support\FrameworkAssets;

it('returns default CDN CSS URL', function () {
    config()->set('trmnl-blade.framework_version', '3.2.0');
    config()->set('trmnl-blade.framework_css_version', null);
    config()->set('trmnl-blade.framework_css_url', null);

    expect(FrameworkAssets::cssUrl())->toBe('https://trmnl.com/css/3.2.0/plugins.css');
});

it('returns custom CSS URL when configured', function () {
    config()->set('trmnl-blade.framework_css_url', 'https://example.com/plugins.css');

    expect(FrameworkAssets::cssUrl())->toBe('https://example.com/plugins.css');
});

it('returns default CDN JS URL', function () {
    config()->set('trmnl-blade.framework_version', '3.2.0');
    config()->set('trmnl-blade.framework_js_version', null);
    config()->set('trmnl-blade.framework_js_url', null);

    expect(FrameworkAssets::jsUrl())->toBe('https://trmnl.com/js/3.2.0/plugins.js');
});

it('returns null for empty theme', function () {
    expect(FrameworkAssets::themeCssUrl(null))->toBeNull();
    expect(FrameworkAssets::themeCssUrl(''))->toBeNull();
});

it('returns default CDN theme URL for built-in themes', function (string $theme, string $expectedUrl) {
    config()->set('trmnl-blade.framework_version', '3.2.0');
    config()->set('trmnl-blade.framework_css_version', null);
    config()->set('trmnl-blade.framework_css_url', null);
    config()->set('trmnl-blade.theme_urls', []);

    expect(FrameworkAssets::themeCssUrl($theme))->toBe($expectedUrl);
})->with([
    ['black-and-yellow', 'https://trmnl.com/css/3.2.0/themes/black-and-yellow-theme.css'],
    ['dark', 'https://trmnl.com/css/3.2.0/themes/dark-theme.css'],
    ['white-and-red', 'https://trmnl.com/css/3.2.0/themes/white-and-red-theme.css'],
]);

it('derives theme URL from custom framework CSS URL', function () {
    config()->set('trmnl-blade.framework_css_url', 'https://example.com/assets/plugins.css');
    config()->set('trmnl-blade.theme_urls', []);

    expect(FrameworkAssets::themeCssUrl('dark'))
        ->toBe('https://example.com/assets/themes/dark-theme.css');
});

it('prefers per-theme URL override', function () {
    config()->set('trmnl-blade.theme_urls', [
        'dark' => 'https://cdn.example.com/custom-dark-theme.css',
    ]);

    expect(FrameworkAssets::themeCssUrl('dark'))
        ->toBe('https://cdn.example.com/custom-dark-theme.css');
});

it('throws for unknown theme', function () {
    FrameworkAssets::themeCssUrl('neon-pink');
})->throws(InvalidArgumentException::class, 'Unknown TRMNL theme [neon-pink]');

it('resolves versioned assets from the configured framework base URL', function (array $settings, ?string $version, array $expected) {
    config()->set('trmnl-blade.framework_version', '3.3.1');
    config()->set('trmnl-blade.framework_css_version', '3.2.0');
    config()->set('trmnl-blade.framework_js_version', '3.1.0');
    config()->set('trmnl-blade.framework_css_url', null);
    config()->set('trmnl-blade.framework_js_url', null);
    config()->set('trmnl-blade.theme_urls', []);
    config()->set('trmnl-blade.framework_base_url', 'https://assets.example.com/trmnl/');

    foreach ($settings as $key => $value) {
        config()->set("trmnl-blade.{$key}", $value);
    }

    expect([
        FrameworkAssets::cssUrl($version),
        FrameworkAssets::jsUrl($version),
        FrameworkAssets::themeCssUrl('dark', $version),
    ])->toBe($expected);
})->with([
    'configured versions' => [[], null, [
        'https://assets.example.com/trmnl/css/3.2.0/plugins.css',
        'https://assets.example.com/trmnl/js/3.1.0/plugins.js',
        'https://assets.example.com/trmnl/css/3.2.0/themes/dark-theme.css',
    ]],
    'explicit version' => [[], '2.3.7', [
        'https://assets.example.com/trmnl/css/2.3.7/plugins.css',
        'https://assets.example.com/trmnl/js/2.3.7/plugins.js',
        'https://assets.example.com/trmnl/css/2.3.7/themes/dark-theme.css',
    ]],
    'URL overrides' => [[
        'framework_css_url' => 'https://override.example.com/plugins.css',
        'framework_js_url' => 'https://override.example.com/plugins.js',
    ], '2.3.7', [
        'https://override.example.com/plugins.css',
        'https://override.example.com/plugins.js',
        'https://override.example.com/themes/dark-theme.css',
    ]],
    'theme override' => [[
        'theme_urls' => ['dark' => 'https://override.example.com/dark.css'],
    ], '2.3.7', [
        'https://assets.example.com/trmnl/css/2.3.7/plugins.css',
        'https://assets.example.com/trmnl/js/2.3.7/plugins.js',
        'https://override.example.com/dark.css',
    ]],
]);
