<?php

namespace Nidavellir\Thor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Nidavellir\Thor\Concerns\AutoRegistersObserversAndPolicies;

class ThorServiceProvider extends ServiceProvider
{
    use AutoRegistersObserversAndPolicies;

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->autoRegisterObservers();
        $this->autoRegisterPolicies();

        DB::prohibitDestructiveCommands(
            $this->app->isProduction()
        );

        Model::shouldBeStrict();
        Model::unguard();
    }
}
