<?php

namespace UbertechZa\TipTapToAsciiDoc;

use Illuminate\Support\ServiceProvider;

class TipTapToAsciiDocServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TipTapToAsciiDocConverter::class, fn () => new TipTapToAsciiDocConverter());
    }
}
