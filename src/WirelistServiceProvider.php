<?php

namespace EduLazaro\Wirelist;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class WirelistServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'wirelist');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/wirelist'),
        ], 'wirelist-views');

        $this->publishes([
            __DIR__.'/../resources/css' => public_path('vendor/wirelist/css'),
        ], 'wirelist-assets');

        // Anonymous components: <x-wirelist> is components/wirelist/index.blade.php and
        // <x-wirelist.item> its item.blade.php.
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $blade->anonymousComponentPath(__DIR__.'/../resources/views/components');
        });
    }
}
