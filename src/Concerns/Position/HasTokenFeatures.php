<?php

namespace Nidavellir\Thor\Concerns\Position;

trait HasTokenFeatures
{
    /**
     * [getTradingPairAttribute description]
     */
    public function getParsedTradingPairAttribute(?string $separator = '')
    {
        return $this->exchangeSymbol
            ->symbol
            ->token.
                    $separator.
               $this->exchangeSymbol
                   ->quote
                   ->canonical;
    }
}
