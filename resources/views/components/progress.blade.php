@props(['variant' => 'bar', 'size' => null])
@php
    $progressRootClass = $variant === 'dots' ? 'progress-dots' : 'progress-bar';
    $progressClass = $progressRootClass.(isset($size) ? ' '.$progressRootClass.'--'.$size : '');
@endphp
<div {{ $attributes->merge(['class' => $progressClass]) }}>
    {{ $slot }}
</div>
