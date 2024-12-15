<?php

namespace Nidavellir\Thor\Observers;

use Nidavellir\Thor\Models\ApplicationLog;
use Nidavellir\Thor\Models\ExchangeSymbol;

class ExchangeSymbolObserver
{
    public function saved(ExchangeSymbol $model): void
    {
        if ($model->wasChanged('leverage_brackes')) {
            ApplicationLog::create([
                'exchange_symbol_id' => $model->id,
                'action_canonical' => 'leverage-brackets-changed',
            ]);
        }
    }
}
