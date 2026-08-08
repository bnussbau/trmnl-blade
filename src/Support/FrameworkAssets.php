<?php

namespace Bnussbau\TrmnlBlade\Support;

use InvalidArgumentException;

class FrameworkAssets
{
    public static function cssUrl(): string
    {
        if ($url = config('trmnl-blade.framework_css_url')) {
            return $url;
        }

        $version = config('trmnl-blade.framework_css_version')
            ?? config('trmnl-blade.framework_version', '3.2.0');

        return "https://trmnl.com/css/{$version}/plugins.css";
    }

    public static function jsUrl(): string
    {
        if ($url = config('trmnl-blade.framework_js_url')) {
            return $url;
        }

        $version = config('trmnl-blade.framework_js_version')
            ?? config('trmnl-blade.framework_version', '3.2.0');

        return "https://trmnl.com/js/{$version}/plugins.js";
    }

    public static function themeCssUrl(?string $theme): ?string
    {
        if ($theme === null || $theme === '') {
            return null;
        }

        static::validateTheme($theme);

        if ($url = config("trmnl-blade.theme_urls.{$theme}")) {
            return $url;
        }

        if ($cssUrl = config('trmnl-blade.framework_css_url')) {
            return dirname($cssUrl)."/themes/{$theme}-theme.css";
        }

        $version = config('trmnl-blade.framework_css_version')
            ?? config('trmnl-blade.framework_version', '3.2.0');

        return "https://trmnl.com/css/{$version}/themes/{$theme}-theme.css";
    }

    protected static function validateTheme(string $theme): void
    {
        $allowedThemes = config('trmnl-blade.themes', []);

        if (! in_array($theme, $allowedThemes, true)) {
            throw new InvalidArgumentException(
                "Unknown TRMNL theme [{$theme}]. Allowed themes: ".implode(', ', $allowedThemes)
            );
        }
    }
}
