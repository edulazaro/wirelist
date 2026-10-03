<?php

namespace EduLazaro\Wirelist\Tests;

use Illuminate\Support\Facades\Blade;

class ListTest extends TestCase
{
    public function test_a_list_is_divided_separated_or_a_grid(): void
    {
        $divided = Blade::render("<x-wirelist>\n<x-wirelist.item title=\"Gala\" />\n</x-wirelist>");
        $separated = Blade::render("<x-wirelist items=\"separated\">\n<x-wirelist.item title=\"Gala\" />\n</x-wirelist>");
        $grid = Blade::render("<x-wirelist layout=\"grid\" flush>\n<x-wirelist.item title=\"Gala\" />\n</x-wirelist>");

        $this->assertStringContainsString('class="wtl-list wtl-list-divided"', $divided);
        $this->assertStringContainsString('<ul class="wtl-list-items" role="list">', $divided);
        $this->assertStringContainsString('class="wtl-list wtl-list-separated"', $separated);
        $this->assertStringContainsString('class="wtl-list wtl-list-separated wtl-list-grid wtl-flush"', $grid);
    }

    public function test_an_item_opens_its_record_without_nesting_its_actions_in_a_link(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-wirelist.item title="Cláusulas suelo" subtitle="Despacho Pérez" href="/cases/1" navigate wire:key="c1">
                <x-slot:badges><span>Banca</span></x-slot:badges>
                <x-slot:aside><span>128</span></x-slot:aside>
                <x-slot:actions><button>⋯</button></x-slot:actions>
                <p>More</p>
            </x-wirelist.item>
            BLADE);

        $this->assertStringContainsString('class="wtl-item wtl-item-linked" wire:key="c1"', $html);
        $this->assertStringContainsString('<a href="/cases/1" class="wtl-item-title wtl-item-link" wire:navigate', $html);
        $this->assertStringContainsString('<span class="wtl-item-badges"><span>Banca</span></span>', $html);
        $this->assertStringContainsString('<p class="wtl-item-subtitle">Despacho Pérez</p>', $html);
        $this->assertStringContainsString('<div class="wtl-item-aside"><span>128</span></div>', $html);
        $this->assertStringContainsString('<div class="wtl-item-actions"><button>⋯</button></div>', $html);
        $this->assertStringContainsString('<p>More</p>', $html);

        // The actions are a sibling of the link, never inside it.
        $this->assertDoesNotMatchRegularExpression('/<a [^>]*>(?:(?!<\/a>).)*<button/s', $html);
    }

    public function test_an_item_without_a_link_has_nothing_to_stretch(): void
    {
        $plain = Blade::render('<x-wirelist.item title="Mensaje #8812" />');
        $action = Blade::render('<x-wirelist.item title="Mensaje #8812" action="open(8812)" />');

        $this->assertStringContainsString('class="wtl-item"', $plain);
        $this->assertStringContainsString('<p class="wtl-item-title">Mensaje #8812</p>', $plain);
        $this->assertStringNotContainsString('wtl-item-subtitle', $plain);
        $this->assertStringNotContainsString('wtl-item-aside', $plain);
        $this->assertStringContainsString('x-on:click="open(8812)" class="wtl-item-title wtl-item-link"', $action);
    }

    public function test_an_empty_list_and_its_footer(): void
    {
        $html = Blade::render("<x-wirelist>\n<x-wirelist.empty>Nothing yet.</x-wirelist.empty>\n<x-slot:footer>\n2 pending\n</x-slot:footer>\n</x-wirelist>");

        $this->assertStringContainsString('<li class="wtl-item wtl-item-empty">Nothing yet.</li>', $html);
        $this->assertMatchesRegularExpression('/<div class="wtl-footer">\s*2 pending\s*<\/div>/', $html);
    }

    public function test_it_draws_with_the_table_package_menu(): void
    {
        $html = Blade::render("<x-wirelist.item title=\"Gala\">\n<x-slot:actions>\n<x-wiretable.menu label=\"Actions\"><x-wiretable.menu-item href=\"/p/1\">Open</x-wiretable.menu-item></x-wiretable.menu>\n</x-slot:actions>\n</x-wirelist.item>");

        $this->assertStringContainsString('class="wtb-menu-trigger"', $html);
        $this->assertStringContainsString('class="wtb-menu-item"', $html);
    }
}
