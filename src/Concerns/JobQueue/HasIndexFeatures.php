<?php

namespace Nidavellir\Thor\Concerns\JobQueue;

use Illuminate\Support\Facades\DB;
use Nidavellir\Thor\Models\JobQueue;

trait HasIndexFeatures
{
    protected function isLast()
    {
        return $this->index == JobQueue::where('block_uuid', $this->block_uuid)->max('index') ||
               $this->index == null;
    }

    protected function isFirst()
    {
        return $this->index == 1 ||
               $this->index == null;
    }

    protected function assignSequentialId()
    {
        if ($this->sequencial_id == null) {
            DB::transaction(function () {
                // Lock the JobQueue table to prevent race conditions
                $maxSequentialId = JobQueue::where('hostname', gethostname())->lockForUpdate()->max('sequencial_id');

                $sequentialId = ($maxSequentialId ?? 0) + 1;

                // Update the jobQueue entry with the new sequential ID
                $this->update(['sequencial_id' => $sequentialId]);
            });
        }
    }
}
