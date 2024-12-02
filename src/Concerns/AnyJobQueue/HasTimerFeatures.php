<?php

namespace Nidavellir\Thor\Concerns\AnyJobQueue;

trait HasTimerFeatures
{
    // Store microtime for duration calculation
    protected $startMicrotime;

    public function startDuration()
    {
        // Record microtime for calculation
        $this->startMicrotime = microtime(true);

        // Update started_at in the database with a proper datetime
        $this->update(['started_at' => now()]);
    }

    public function finalizeDuration()
    {
        if (! isset($this->startMicrotime)) {
            throw new \LogicException('startDuration must be called before finalizeDuration.');
        }

        // Calculate duration in milliseconds
        $duration = intval((microtime(true) - $this->startMicrotime) * 1000);

        // Update the database with the calculated duration
        $this->update(['duration' => $duration]);
    }
}
