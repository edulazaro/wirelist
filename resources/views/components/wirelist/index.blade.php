@props(['items' => 'divided', 'layout' => 'list', 'flush' => false])

{{-- A list of records without column headers, sibling of x-wiretable: each record an
     `x-wirelist.item` with its title, badges, a muted line, what goes on the right (figures, a
     status) and its "⋯" (x-wiretable.menu).

     `items`: `divided` (one frame, a line between records) or `separated` (each record a card).
     `layout`: `list`, or `grid` (cards side by side, as many as fit). The same markup draws
     both, so a list/grid switch is just `:layout="$view"`. `flush`: no frame, inside a card.

     <x-wirelist items="separated">
         @foreach ($cases as $case)
             <x-wirelist.item wire:key="…" :title="$case->title" :subtitle="$case->company" :href="…">
                 <x-slot:badges>…</x-slot:badges>
                 <x-slot:aside>…</x-slot:aside>
                 <x-slot:actions><x-wiretable.menu …>…</x-wiretable.menu></x-slot:actions>
             </x-wirelist.item>
         @endforeach
     </x-wirelist> --}}
<div {{ $attributes->class([
    'wtl-list',
    'wtl-list-separated' => $items === 'separated' || $layout === 'grid',
    'wtl-list-divided' => $items !== 'separated' && $layout !== 'grid',
    'wtl-list-grid' => $layout === 'grid',
    'wtl-flush' => $flush,
]) }}>
    <ul class="wtl-list-items" role="list">
        {{ $slot }}
    </ul>
@isset($footer)
    <div class="wtl-footer">{{ $footer }}</div>
@endisset
</div>
