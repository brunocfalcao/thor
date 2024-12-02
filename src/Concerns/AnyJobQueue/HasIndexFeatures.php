<?php

namespace Nidavellir\Thor\Concerns\AnyJobQueue;

use Illuminate\Support\Facades\DB;

trait HasIndexFeatures
{
    public function isLast()
    {
        $modelClass = get_class($this);

        return $this->index == $modelClass::where('block_uuid', $this->block_uuid)->max('index') ||
               $this->index == null;
    }

    public function isFirst()
    {
        return $this->index == 1 ||
               $this->index == null;
    }

    public function assignSequentialId()
    {
        if ($this->sequencial_id == null) {
            DB::transaction(function () {
                $modelClass = get_class($this);

                // Lock the table to prevent race conditions
                $maxSequentialId = $modelClass::where('hostname', gethostname())->lockForUpdate()->max('sequencial_id');

                $sequentialId = ($maxSequentialId ?? 0) + 1;

                // Update the job entry with the new sequential ID
                $this->update(['sequencial_id' => $sequentialId]);
            });
        }
    }
}
