---
name: trmnl-blade-development
description: "Build TRMNL Framework 3.2 Blade UIs with bnussbau/trmnl-blade (x-trmnl:: components), layout structure, tables, charts, themes, mashups, and anti-patterns."
---

# TRMNL Blade development

## When to use this skill

Use this skill when editing or creating Blade views that use `bnussbau/trmnl-blade`, `x-trmnl::` components, TRMNL plugin screens, or local TRMNL previews. Do not load this for unrelated Laravel work.

Blade components are prefixed `x-trmnl::` for the official [TRMNL Framework 3.2](https://trmnl.com/framework/docs/3.2). Use these components for framework structure, then use documented TRMNL classes/utilities for fine-grained spacing, typography, color, overflow, responsive behavior, and charts. Do not invent parallel components.

**Key docs:** [Structure](https://trmnl.com/framework/docs/3.2/structure), [Grid](https://trmnl.com/framework/docs/3.2/grid), [Table](https://trmnl.com/framework/docs/3.2/table), [Chart](https://trmnl.com/framework/docs/3.2/chart), [Themes](https://trmnl.com/framework/docs/3.2/themes), [Tokens](https://trmnl.com/framework/docs/3.2/tokens), [Colors](https://trmnl.com/framework/docs/3.2/colors).

---

## Mandatory structure

`Screen -> (optional Mashup) -> View -> Layout (+ optional Title Bar)`

- **Local/preview pages:** `<x-trmnl::screen>` → `<x-trmnl::view>` → exactly one `<x-trmnl::layout>` per view. Optional `<x-trmnl::title-bar>` is a sibling of layout inside the view.
- **TRMNL-hosted plugin bodies:** the platform may provide screen/view. Emit only `<x-trmnl::layout>` (+ optional title bar) unless the user asks for a full preview scaffold.
- Put grids, columns, flex, tables, rich text, items, stats, maps, and chart containers **inside** the layout. Never replace the layout with content.

---

## Layout choice

- **Grid:** fixed rhythm, equal tracks, explicit spans. Dashboards, KPI cards, editorial blocks, alignment-sensitive layouts.
- **Flex:** row/column alignment inside a region or grid cell. Small groups whose size follows content.
- **Columns:** repeated same-type content where the framework distributes columns/overflow.
- **Table:** structured rows/columns of comparable data.
- **Map:** `<x-trmnl::map>` for a still vector map. Pass `id`, `preset`, `center` (`[lng, lat]`), and `zoom` for a place-map. Slot overlay cards; `marker` for a center dot. Omit `center`/`zoom` and write `TRMNLMaps` JS for routes. Do not invent route/polyline/`fit` props.
- **Chart/graph:** `<x-trmnl::chart>` for an empty Highcharts container. Pass `id`. Write `TRMNLCharts` JS yourself. Do not wrap Highcharts options as Blade props.

---

## Grid recipes

Use `<x-trmnl::grid cols="n">` inside layout for strict equal-width columns. Use `<x-trmnl::col span="n">` for wider cells; spans in a visual row should add up to the grid column count. Use `position="start|center|end"` to align content vertically inside a col. Nest `<x-trmnl::flex>` inside a cell for internal row/column alignment.

For wrapping grids, add framework classes directly: `class="grid--wrap grid--min-{size}"` when responsive minimum track size is needed.

**KPI grid with spans:**

```blade
<x-trmnl::layout direction="col" stretch="default">
    <x-trmnl::grid cols="4" class="gap--medium">
        <x-trmnl::col span="2">
            <x-trmnl::item>
                <x-trmnl::label>Revenue</x-trmnl::label>
                <x-trmnl::value size="large">$12,840</x-trmnl::value>
            </x-trmnl::item>
        </x-trmnl::col>

        <x-trmnl::col>
            <x-trmnl::item>
                <x-trmnl::label>Orders</x-trmnl::label>
                <x-trmnl::value>384</x-trmnl::value>
            </x-trmnl::item>
        </x-trmnl::col>

        <x-trmnl::col>
            <x-trmnl::item>
                <x-trmnl::label>Refunds</x-trmnl::label>
                <x-trmnl::value>7</x-trmnl::value>
            </x-trmnl::item>
        </x-trmnl::col>
    </x-trmnl::grid>
</x-trmnl::layout>
```

---

## Tables

Use `<x-trmnl::table>` with native `<thead>`, `<tbody>`, `<tr>`, `<th>`, `<td>`. Sizes map to framework classes: `base`, `large`, `xlarge`, `small`, `xsmall`; prefer `small` or `xsmall` for dense plugin screens. `condensed` exists as an older alias; do not use it as the first choice.

For predictable rows, clamp long cell content with `data-clamp`. For indexed tables, the framework requires `table--indexed` on the table and `<x-trmnl::meta><span class="index">...</span></x-trmnl::meta>` in the indexed cell. The package table component exposes `size` only, so use native framework table markup when extra table classes such as `table--indexed` are needed. Large tables can rely on framework [Table Overflow](https://trmnl.com/framework/docs/3.2/table_overflow) behavior, including the trailing "and X more" row when content exceeds height.

**Dense table with clamp and index:**

```blade
<table class="table table--small table--indexed">
    <thead>
        <tr>
            <th></th>
            <th>Account</th>
            <th class="text--right">MRR</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($accounts as $account)
            <tr>
                <td>
                    <x-trmnl::meta>
                        <span class="index">{{ $loop->iteration }}</span>
                    </x-trmnl::meta>
                </td>
                <td><span data-clamp="1">{{ $account->name }}</span></td>
                <td class="text--right">${{ number_format($account->mrr) }}</td>
                <td><x-trmnl::label variant="outline">{{ $account->status }}</x-trmnl::label></td>
            </tr>
        @endforeach
    </tbody>
</table>
```

---

## Maps

Use `<x-trmnl::map>` inside layout. MapLibre is injected once above the map container. See [Map](https://trmnl.com/framework/docs/3.3/map).

```blade
<x-trmnl::layout direction="col">
    <x-trmnl::map id="map-streets" preset="streets" :center="[-84.3885, 33.7554]" :zoom="13" />
</x-trmnl::layout>
```

```blade
<x-trmnl::map id="map-overlay" preset="streets" :center="$stadium" :zoom="15" :marker="true">
    <div class="absolute top--2 left--2 z--2 p--4 w--max-60 bg--canvas outline outline--muted">
        {{-- overlay card --}}
    </div>
</x-trmnl::map>
```

Do not wrap `route()`, polylines, `fit()`, or tiles as Blade props. For those, omit `center`/`zoom` and call `TRMNLMaps` yourself.

---

## Charts and graphs

Use `<x-trmnl::chart>` inside layout. Highcharts and pattern-fill are injected once above the container. See [Chart](https://trmnl.com/framework/docs/3.3/chart).

```blade
<x-trmnl::layout direction="col" stretch="default">
    <x-trmnl::grid cols="3" class="gap--medium">
        <x-trmnl::item>
            <x-trmnl::value>{{ number_format($pageviews) }}</x-trmnl::value>
            <x-trmnl::label>Pageviews</x-trmnl::label>
        </x-trmnl::item>
        <x-trmnl::item>
            <x-trmnl::value>{{ number_format($visitors) }}</x-trmnl::value>
            <x-trmnl::label>Visitors</x-trmnl::label>
        </x-trmnl::item>
        <x-trmnl::item>
            <x-trmnl::value>{{ $conversionRate }}%</x-trmnl::value>
            <x-trmnl::label>Conversion</x-trmnl::label>
        </x-trmnl::item>
    </x-trmnl::grid>

    <x-trmnl::chart id="traffic-chart" />
</x-trmnl::layout>

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

Chart rules for TRMNL renders:

- Use a clear stat/header grid (`value`, `label`, `description`) around the graph.
- Size the container with extra classes (e.g. `class="h--48"`); `height: null` fills remaining space.
- Disable animations, or the screenshot service may capture a partial chart (`TRMNLCharts.options()` already does this).
- Use `TRMNLCharts.series()` for colors so the chart follows the device and theme. Call `applySwatches()` when using `data-chart-series` legend marks.
- Load `highcharts-more.js` yourself for gauges. Load Chartkick yourself if you use it.
- Use `<x-trmnl::progress>` only for simple bar/dot progress, not as a full chart substitute.

Do not wrap Highcharts options (`type`, `series`, `height`, axes) as Blade props.

---

## Mashups (optional composition)

Mashups place different layouts/plugins on one screen; they are not the default for a single layout. Use `<x-trmnl::mashup mashupLayout="...">` with patterns like `1Lx1R`, `1Tx1B`, `2x2`, `1Lx2R`, `2Lx1R`, `2Tx1B`, `1Tx2B`. Each direct child is a `<x-trmnl::view size="full|half_vertical|half_horizontal|quadrant">`; each view still owns exactly one layout.

---

## Themes

Framework 3.2 themes are opt-in stylesheets loaded alongside `plugins.css`. Set the `theme` prop on `<x-trmnl::screen>` to apply a built-in theme:

- `black-and-yellow` — high-contrast yellow accent
- `dark` — full dark color statement (prefer this over `darkMode` for dark rendering)
- `white-and-red` — white and red accent palette

```blade
<x-trmnl::screen theme="dark">
    <x-trmnl::view>
        <x-trmnl::layout>...</x-trmnl::layout>
    </x-trmnl::view>
</x-trmnl::screen>
```

The screen component loads the matching theme CSS from the TRMNL CDN and emits `screen--theme-{id}`. Themes are a complete color statement: `screen--dark-mode` has no effect while a theme is active unless the theme stylesheet opts into `.screen--theme-{id}.screen--dark-mode`. Use `darkMode` only on unthemed screens. See [Themes](https://trmnl.com/framework/docs/3.2/themes).

Override theme URLs in config via `theme_urls`.

---

## Component map

**Scaffold:** `screen` — props: `noBleed`, `darkMode` (emits `screen--dark-mode`), `theme` (`black-and-yellow`|`dark`|`white-and-red`), `deviceVariant` (default `og`), `deviceOrientation`, `colorDepth` (default `1bit`), `scaleLevel`, `fonts` (default `trmnl`). Emits framework CSS/JS and optional theme CSS from package config.

**Structure:** `mashup` (`mashupLayout`), `view` (`size`, default `full`), `layout` (`direction`: row|col, `alignment`: left|right|center-x|top|center-y|bottom|center, `stretch`: default → `layout--stretch`, stretch-x, stretch-y), `columns`/`column`, `flex`, `grid`, `col`, `aspect`.

**Content/UI:** `richtext` (`align`, `gapSize`), `content` (`contentAlignment`, `textAlignment`, `gapSize`), `item`, `table`, `map` (`id` required, `preset` default `streets`, optional `center` `[lng, lat]`, `zoom`, `marker`; overlay slot), `chart` (`id` required; Highcharts injected once; write `TRMNLCharts` JS yourself), `progress` (`variant`: bar|dots, optional `size`) + `track`, `meta`, `divider`, `background` (`color` → `bg--{color}`).

**Typography:** `text` (`alignment`, `shading`), `title` (`size=small` optional), `value` (`size`, `textStroke`), `label` (`variant`, `size`), `description`, `clamp` (`lines`, default `1`).

**Chrome:** `title-bar` (`title`, optional `image` URL or `image="inline"` with slot, optional `instance`).

**Do not use:** `<x-trmnl::markdown>` (deprecated; use `richtext`). Do not add route/polyline/`fit` props to `map`. Do not wrap Highcharts options as Blade props on `chart`.

---

## Anti-patterns

- Omitting `layout` or putting main content beside it.
- Multiple layout roots in one view.
- Treating mashups as required for ordinary dashboards.
- Using `markdown` or invented components.
- Hand-rolling scaffold HTML for local previews instead of package components.

---

## Install

```bash
composer require bnussbau/trmnl-blade
```

Optional: `php artisan vendor:publish --tag="trmnl-blade-config"`. Config prefix `trmnl` → `<x-trmnl::component>`.
