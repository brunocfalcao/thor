<?php

namespace Nidavellir\Thor\Concerns\CoreJobQueue;

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

                // Use global lock to ensure no race conditions across hosts
                $maxSequentialId = $modelClass::lockForUpdate()->max('sequencial_id');

                $sequentialId = ($maxSequentialId ?? 0) + 1;

                // Update the job entry with the new sequential ID
                $this->update(['sequencial_id' => $sequentialId]);
            });
        }
    }

    public function getPrevious()
    {
        $modelClass = get_class($this);

        // Return an empty collection if index is null or non-positive
        if (is_null($this->index) || ! is_numeric($this->index) || $this->index <= 1) {
            return $modelClass::newCollection();
        }

        // Calculate the previous index
        $previousIndex = $this->index - 1;

        // Fetch all job queues with the previous index and the same block_uuid
        return $modelClass::where('block_uuid', $this->block_uuid)
            ->where('index', $previousIndex)
            ->get();
    }

    public function getByCanonical(string $canonical)
    {
        $modelClass = get_class($this);

        // Ensure the block_uuid exists before querying
        if ($this->block_uuid == null) {
            return $modelClass::newCollection();
        }

        // Fetch all job queues with the same block_uuid and canonical value
        return $modelClass::where('block_uuid', $this->block_uuid)
            ->where('canonical', $canonical)
            ->get();
    }
}
