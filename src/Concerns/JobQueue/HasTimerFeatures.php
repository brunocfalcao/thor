<?php

namespace Nidavellir\Thor\Concerns\JobQueue;

trait HasTimerFeatures
{
    private function startDuration()
    {
        $this->startedAt = microtime(true);
        $this->update(['started_at' => now()]);
    }

    protected function finalizeDuration()
    {
        $duration = intval((microtime(true) - $this->startedAt) * 1000);
        $this->update(['duration' => $duration]);
    }
}
