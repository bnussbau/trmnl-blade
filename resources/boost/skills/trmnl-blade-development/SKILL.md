---
name: trmnl-blade-development
description: "Build TRMNL Framework 3.1 Blade UIs with bnussbau/trmnl-blade (x-trmnl:: components), layout structure, tables, charts, mashups, and anti-patterns."
---

# TRMNL Blade development

## When to use this skill

Use this skill when editing or creating Blade views that use `bnussbau/trmnl-blade`, `x-trmnl::` components, TRMNL plugin screens, or local TRMNL previews. Do not load this for unrelated Laravel work.

Blade components are prefixed `x-trmnl::` for the official [TRMNL Framework 3.1](https://trmnl.com/framework/docs/3.1). Use these components for framework structure, then use documented TRMNL classes/utilities for fine-grained spacing, typography, color, overflow, responsive behavior, and charts. Do not invent parallel components.

**Key docs:** [Structure](https://trmnl.com/framework/docs/3.1/structure), [Grid](https://trmnl.com/framework/docs/3.1/grid), [Table](https://trmnl.com/framework/docs/3.1/table), [Chart](https://trmnl.com/framework/docs/3.1/chart), [Tokens](https://trmnl.com/framework/docs/3.1/tokens), [Colors](https://trmnl.com/framework/docs/3.1/colors).

---

## Mandatory structure

`Screen -> (optional Mashup) -> View -> Layout (+ optional Title Bar)`

- **Local/preview pages:** `<x-trmnl::screen>` → `<x-trmnl::view>` → exactly one `<x-trmnl::layout>` per view. Optional `<x-trmnl::title-bar>` is a sibling of layout inside the view.
- **TRMNL-hosted plugin bodies:** the platform may provide screen/view. Emit only `<x-trmnl::layout>` (+ optional title bar) unless the user asks for a full preview scaffold.
- Put grids, columns, flex, tables, rich text, items, stats, and chart containers **inside** the layout. Never replace the layout with content.

---

## Layout choice

- **Grid:** fixed rhythm, equal tracks, explicit spans. Dashboards, KPI cards, editorial blocks, alignment-sensitive layouts.
- **Flex:** row/column alignment inside a region or grid cell. Small groups whose size follows content.
- **Columns:** repeated same-type content where the framework distributes columns/overflow.
- **Table:** structured rows/columns of comparable data.
- **Chart/graph:** no Blade component; use framework-compatible chart markup/JS inside layout.

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

For predictable rows, clamp long cell content with `data-clamp`. For indexed tables, the framework requires `table--indexed` on the table and `<x-trmnl::meta><span class="index">...</span></x-trmnl::meta>` in the indexed cell. The package table component exposes `size` only, so use native framework table markup when extra table classes such as `table--indexed` are needed. Large tables can rely on framework [Table Overflow](https://trmnl.com/framework/docs/3.1/table_overflow) behavior, including the trailing "and X more" row when content exceeds height.

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

## Charts and graphs

This package has **no** `<x-trmnl::chart>` component. Do not invent one. Build chart screens with TRMNL layout components plus raw chart markup/JS following the framework [Chart](https://trmnl.com/framework/docs/3.1/chart) docs.

Chart rules for TRMNL renders:

- Use a clear stat/header grid (`value`, `label`, `description`) around the graph.
- Any CDN JS chart library may be used; TRMNL docs use Highcharts and Chartkick.
- Disable animations, hover states, tooltips, and credits where possible.
- Use transparent chart backgrounds, black/grayscale colors, and pattern fills for multi-series 1-bit displays.
- Prefer fixed or container-filling height; if using Highcharts/Chartkick, docs recommend `height: null` for fill behavior or explicit pixel heights when needed.
- Ensure chart initialization completes before TRMNL captures the render.
- Use `<x-trmnl::progress>` only for simple bar/dot progress, not as a full chart substitute.

**Chart screen skeleton:**

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

    <div id="traffic-chart"></div>
</x-trmnl::layout>

<script>
document.addEventListener('DOMContentLoaded', () => {
    Highcharts.chart('traffic-chart', {
        chart: { type: 'spline', animation: false, backgroundColor: 'transparent', height: 260 },
        title: { text: null },
        legend: { enabled: false },
        tooltip: { enabled: false },
        credits: { enabled: false },
        plotOptions: { series: { animation: false, enableMouseTracking: false, marker: { enabled: false } } },
        series: [{ data: @json($series), color: '#000000', lineWidth: 4 }]
    });
});
</script>
```

---

## Mashups (optional composition)

Mashups place different layouts/plugins on one screen; they are not the default for a single layout. Use `<x-trmnl::mashup mashupLayout="...">` with patterns like `1Lx1R`, `1Tx1B`, `2x2`, `1Lx2R`, `2Lx1R`, `2Tx1B`, `1Tx2B`. Each direct child is a `<x-trmnl::view size="full|half_vertical|half_horizontal|quadrant">`; each view still owns exactly one layout.

---

## Component map

**Scaffold:** `screen` — props: `noBleed`, `darkMode`, `deviceVariant` (default `og`), `deviceOrientation`, `colorDepth` (default `1bit`), `scaleLevel`, `fonts` (default `trmnl`). Emits framework CSS/JS from package config.

**Structure:** `mashup` (`mashupLayout`), `view` (`size`, default `full`), `layout` (`direction`: row|col, `alignment`: left|right|center-x|top|center-y|bottom|center, `stretch`: default → `layout--stretch`, stretch-x, stretch-y), `columns`/`column`, `flex`, `grid`, `col`, `aspect`.

**Content/UI:** `richtext` (`align`, `gapSize`), `content` (`contentAlignment`, `textAlignment`, `gapSize`), `item`, `table`, `progress` (`variant`: bar|dots, optional `size`) + `track`, `meta`, `divider`, `background` (`color` → `bg--{color}`).

**Typography:** `text` (`alignment`, `shading`), `title` (`size=small` optional), `value` (`size`, `textStroke`), `label` (`variant`, `size`), `description`, `clamp` (`lines`, default `1`).

**Chrome:** `title-bar` (`title`, optional `image` URL or `image="inline"` with slot, optional `instance`).

**Do not use:** `<x-trmnl::markdown>` (deprecated; use `richtext`) or invented components like `<x-trmnl::chart>`.

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
