<?php

namespace Nidavellir\Thor;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Nidavellir\Thor\Models\CoreJobQueue;
use Nidavellir\Thor\Models\ApplicationLog;
use Nidavellir\Thor\Observers\CoreJobQueueObserver;
use Nidavellir\Thor\Observers\ApplicationLogObserver;
use Nidavellir\Thor\Concerns\AutoRegistersObserversAndPolicies;

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
        ApplicationLog::observe(ApplicationLogObserver::class);
    }
}
