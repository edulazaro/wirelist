![Wirelist](art/banner.png)

# wirelist

Lists for Laravel, Livewire and Alpine: records as divided rows, separate cards or a grid, each one opening its record, with a row actions menu and load more. Pure CSS, no Tailwind or Bootstrap needed. Built on [wiretables](https://github.com/edulazaro/wiretables) and part of the `wire*` family: themeable through the shared `data-wire-theme` attribute, coherent with [wiremodal](https://github.com/edulazaro/wiremodal), [wiretoast](https://github.com/edulazaro/wiretoast) and [wirepicker](https://github.com/edulazaro/wirepicker).

Divided, separated or grid · the whole card opens the record · badges, aside, actions · load more · 12 themes · 0 runtime deps.

## When a list and when a table

Use [wiretables](https://github.com/edulazaro/wiretables) when the records share the same data and are compared down a column: dates, statuses, amounts, with headers to sort by. Use wirelist when each record reads as a card: a title with its badges, a muted line, a figure or a status on the right, and when you want them side by side in a grid. Both share the menu, the footer, load more, the traits and the themes, so they sit together on one screen.

## Install

```bash
composer require edulazaro/wirelist
php artisan vendor:publish --tag=wiretables-assets
php artisan vendor:publish --tag=wirelist-assets
```

It requires wiretables, whose tokens it draws with. Load wiretables' core, then the list's core, and, if you want one, the same theme from both:

```blade
<link rel="stylesheet" href="{{ asset('vendor/wiretables/css/wiretables-core.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/wiretables/css/themes/studio.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/wirelist/css/wirelist-core.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/wirelist/css/themes/studio.css') }}">
```

Or with Vite:

```css
@import "../../vendor/edulazaro/wiretables/resources/css/wiretables-core.css";
@import "../../vendor/edulazaro/wiretables/resources/css/themes/studio.css";
@import "../../vendor/edulazaro/wirelist/resources/css/wirelist-core.css";
@import "../../vendor/edulazaro/wirelist/resources/css/themes/studio.css";
```

`wirelist.css` holds the core and every theme in one file. Adding themes never makes `wirelist-core.css` bigger.

## A list

```blade
<x-wirelist items="separated">
    @forelse ($cases as $case)
        <x-wirelist.item wire:key="case-{{ $case->id }}" :title="$case->title" :subtitle="$case->company" :href="route('cases.show', $case)">
            <x-slot:badges>
                @foreach ($case->tags as $tag)
                    <span class="badge">{{ $tag->name }}</span>
                @endforeach
            </x-slot:badges>
            <x-slot:aside>
                <span>{{ $case->members_count }} members</span>
                <span class="status">{{ $case->status }}</span>
            </x-slot:aside>
            <x-slot:actions>
                <x-wiretable.menu :label="'Actions of '.$case->title">
                    <x-wiretable.menu-item :href="route('cases.show', $case)">Open</x-wiretable.menu-item>
                    <x-wiretable.menu-item wire:click="archive({{ $case->id }})" danger>Archive…</x-wiretable.menu-item>
                </x-wiretable.menu>
            </x-slot:actions>
        </x-wirelist.item>
    @empty
        <x-wirelist.empty>No cases yet.</x-wirelist.empty>
    @endforelse

    <x-slot:footer>
        {{ $cases->links() }}
    </x-slot:footer>
</x-wirelist>
```

## Three shapes, one markup

- `items="divided"` (the default): one frame, a line between records. A queue, a feed of short items, a dashboard list.
- `items="separated"`: each record a card of its own, with a little room between them.
- `layout="grid"`: cards side by side, as many as fit (`--wtl-grid-min`, 16rem by default). The `leading` slot becomes a cover: an image runs edge to edge on top, and the `aside` sits at the foot of the card, aligned across the row.

The markup does not change between them, so a list/grid switch is a Livewire property:

```blade
<x-wirelist :layout="$view">…</x-wirelist>
```

`flush` drops the frame, for a list inside a card of its own.

## The item

`<x-wirelist.item>` takes a `title` (or a `title` slot when it needs markup), a muted `subtitle`, and slots for everything around them:

| Slot | |
|---|---|
| `leading` | An avatar or an icon on the left; in a grid, the cover image |
| `badges` | Beside the title: tags, a "draft", a category |
| `aside` | On the right: counts, a status, a date |
| `actions` | The "⋯" (`x-wiretable.menu`) or a button |
| `header` | A strip above the record: a status band, a date |
| default | More lines under the subtitle |

With `href` the whole item opens the record (`navigate` adds `wire:navigate`); with `action`, an Alpine expression, it opens in place. The title's link is stretched over the item, while the badges, the aside, the actions and any link or button in the content keep their own clicks. Nothing interactive is nested inside a link, which HTML does not allow and which breaks keyboards and screen readers.

On a phone the actions stay beside the title and the aside goes under both.

## Load more and sorting

wiretables' traits work the same here:

```php
use EduLazaro\Wiretables\Concerns\WithLoadMore;

$cases = $this->loadMoreFrom(LegalCase::query()->latest());
```

```blade
<x-wirelist>…</x-wirelist>
<x-wiretable.load-more :show="$hasMore" />
```

## Options

| Component | Attribute | |
|---|---|---|
| `x-wirelist` | `items` | `divided` or `separated` |
| | `layout` | `list` or `grid` |
| | `flush` | No frame, for a list inside a card |
| | slot `footer` | Under the records |
| `x-wirelist.item` | `title`, `subtitle`, `href`, `action`, `navigate` | The record; slots above |
| `x-wirelist.empty` | | The item shown when the list is empty |

Any other attribute (`class`, `wire:key`, `data-*`) lands on the element.

## Themes

The family's 12 themes, by the same `data-wire-theme` attribute on `<html>`: soft, glass, gradient, neon, minimal, claude, chatgpt, studio, synthwave, megaflow, brutalist and toxic, each with its dark mode (toxic, like neon, is dark only). wiretables' theme file brings the colours tables and lists share; this package's file of the same name adds what only a list needs: minimal's and gradient's stripe on the left, claude's line on top, glass's blur, synthwave's gradient, toxic's lime titles and lit edge, the header strip's colours.

## Custom look

Colours, radius, lines and the menu come from wiretables' tokens (`--wtb-bg`, `--wtb-text`, `--wtb-muted`, `--wtb-line`, `--wtb-row-hover`, `--wtb-radius`, `--wtb-shadow`…), so one setting reaches tables and lists alike. The list's own shape:

| Token | |
|---|---|
| `--wtl-item-padding` | Inside an item |
| `--wtl-item-gap` | Between separated items |
| `--wtl-subtitle-font-size` | The muted line and the aside |
| `--wtl-grid-min`, `--wtl-grid-gap` | The grid's narrowest card and the room between cards |
| `--wtl-cover-ratio` | A grid card's cover, `16 / 10` by default |

## Anatomy

```
.wtl-list                  .wtl-list-divided, .wtl-list-separated, .wtl-list-grid, .wtl-flush
  ul.wtl-list-items
    li.wtl-item            .wtl-item-linked, .wtl-item-empty
      .wtl-item-header
      .wtl-item-main
        .wtl-item-leading
        .wtl-item-body     .wtl-item-heading (.wtl-item-title, .wtl-item-badges), .wtl-item-subtitle, .wtl-item-content
        .wtl-item-aside
        .wtl-item-actions
  .wtl-footer
```

## Tests

```bash
composer install
vendor/bin/phpunit
```

## Sponsors

wirelist is supported by the following sponsors. Thank you for keeping it growing:

<p>
  <a href="https://kenodo.com"><img src="art/logo-kenodo.png" width="24" alt="Kenodo"></a>&nbsp;<a href="https://kenodo.com">Kenodo</a>&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="https://andorradev.com"><img src="art/logo-andorradev.png" width="24" alt="AndorraDev"></a>&nbsp;<a href="https://andorradev.com">AndorraDev</a>
</p>

## Author

Created by [Edu Lazaro](https://edulazaro.com)

## License

wirelist is open-sourced software licensed under the [MIT license](LICENSE).
