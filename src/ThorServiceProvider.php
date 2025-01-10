<?php

namespace Nidavellir\Thor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Nidavellir\Thor\Concerns\AutoRegistersObserversAndPolicies;
use Nidavellir\Thor\Models\CoreJobQueue;
use Nidavellir\Thor\Observers\CoreJobQueueObserver;

class ThorServiceProvider extends ServiceProvider
{
    use AutoRegistersObserversAndPolicies;

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        DB::prohibitDestructiveCommands(
            $this->app->isProduction()
        );

        Model::shouldBeStrict();
        Model::unguard();

        $this->registerObservers();
    }

    protected function registerObservers()
    {
        CoreJobQueue::observe(CoreJobQueueObserver::class);
    }
}
