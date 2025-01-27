<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Nidavellir\Mjolnir\Concerns\Models\Order\HasApiFeatures;
use Nidavellir\Thor\Concerns\Order\HasStatusesFeatures;

class Order extends Model
{
    use HasApiFeatures;
    use HasStatusesFeatures;

    protected $casts = [
        'skip_observer' => 'boolean',
        'api_result' => 'array',
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function scopeActive(Builder $query)
    {
        $query->whereIn('orders.status', ['NEW', 'PARTIALLY_FILLED']);
    }

    /**
     * Accessor to remove trailing zeros from the quantity attribute.
     */
    public function getQuantityAttribute(?float $value): ?float
    {
        return $value !== null ? $this->removeTrailingZeros($value) : null;
    }

    /**
     * Accessor to remove trailing zeros from the price attribute.
     */
    public function getPriceAttribute(?float $value): ?float
    {
        return $value !== null ? $this->removeTrailingZeros($value) : null;
    }

    /**
     * Normalize a number by removing trailing zeros.
     */
    protected function removeTrailingZeros(float $number): float
    {
        return (float) rtrim(rtrim(number_format($number, 10, '.', ''), '0'), '.');
    }
}
