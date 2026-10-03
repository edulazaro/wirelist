@props(['title' => null, 'subtitle' => null, 'href' => null, 'action' => null, 'navigate' => false])

{{-- One record of an `x-wirelist`. With `href` the whole item opens it: the title's link is
     stretched over the item, so the badges, the aside and the actions stay clickable on their
     own instead of being nested inside a link (which HTML does not allow). `action` opens it in
     place (an Alpine expression).

     Slots: `leading` (an avatar, an icon, an image in a grid), `badges` (beside the title),
     `aside` (on the right: counts, a status, a date), `actions` (the "⋯"), `header` (a strip
     above the record) and the default slot (more lines under the subtitle). A `title` slot
     replaces the `title` attribute when the title needs markup. --}}
<li {{ $attributes->class(['wtl-item', 'wtl-item-linked' => $href || $action]) }}>
    @isset($header)
        <div class="wtl-item-header">{{ $header }}</div>
    @endisset

    <div class="wtl-item-main">
        @isset($leading)
            <div class="wtl-item-leading">{{ $leading }}</div>
        @endisset

        <div class="wtl-item-body">
            <div class="wtl-item-heading">
                @if ($href)
                    <a href="{{ $href }}" class="wtl-item-title wtl-item-link"@if ($navigate) wire:navigate @endif>{{ $title ?? '' }}</a>
                @elseif ($action)
                    <button type="button" x-on:click="{{ $action }}" class="wtl-item-title wtl-item-link">{{ $title ?? '' }}</button>
                @else
                    <p class="wtl-item-title">{{ $title ?? '' }}</p>
                @endif

                @isset($badges)
                    <span class="wtl-item-badges">{{ $badges }}</span>
                @endisset
            </div>

            @if (filled($subtitle))
                <p class="wtl-item-subtitle">{{ $subtitle }}</p>
            @endif

            @if ($slot->isNotEmpty())
                <div class="wtl-item-content">{{ $slot }}</div>
            @endif
        </div>

        @isset($aside)
            <div class="wtl-item-aside">{{ $aside }}</div>
        @endisset

        @isset($actions)
            <div class="wtl-item-actions">{{ $actions }}</div>
        @endisset
    </div>
</li>
