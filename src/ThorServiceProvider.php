<?php

namespace Nidavellir\Thor;

use Illuminate\Support\ServiceProvider;

class ThorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
