@props([
    'noBleed' => false,
    'darkMode' => false,
    'theme' => null,
    'deviceVariant' => 'og',
    'deviceOrientation' => null,
    'colorDepth' => '1bit',
    'scaleLevel' => null,
    'fonts' => 'trmnl',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=Inter:300,400,500" rel="stylesheet" />
    <link rel="stylesheet" href="{{ \Bnussbau\TrmnlBlade\Support\FrameworkAssets::cssUrl() }}" />
    @if ($themeCssUrl = \Bnussbau\TrmnlBlade\Support\FrameworkAssets::themeCssUrl($theme))
        <link rel="stylesheet" href="{{ $themeCssUrl }}" />
    @endif
    <script src="{{ \Bnussbau\TrmnlBlade\Support\FrameworkAssets::jsUrl() }}"></script>
    <title>{{ $title ?? config('app.name') }}</title>
</head>
<body class="environment trmnl">
    <div class="screen {{ $noBleed ? 'screen--no-bleed' : '' }} {{ $darkMode ? 'screen--dark-mode' : '' }} {{ $theme ? 'screen--theme-' . $theme : '' }} {{ $deviceVariant ? 'screen--' . $deviceVariant : '' }} {{ $deviceOrientation ? 'screen--' . $deviceOrientation : '' }} {{ $colorDepth ? 'screen--' . $colorDepth : '' }} {{ $scaleLevel ? 'screen--scale-' . $scaleLevel : '' }} {{ $fonts ? 'screen--fonts-' . $fonts : 'trmnl' }}">
        {{ $slot }}
    </div>
</body>
</html>
