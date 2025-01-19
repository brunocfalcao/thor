<?php

namespace Nidavellir\Thor\Models;

use Illuminate\Database\Eloquent\Model;
use Nidavellir\Mjolnir\Concerns\Models\Order\HasApiFeatures;
use Nidavellir\Thor\Concerns\Order\HasStatusesFeatures;

class Order extends Model
{
    use HasApiFeatures;
    use HasStatusesFeatures;

    protected $casts = [
        'api_result' => 'array',
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
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
