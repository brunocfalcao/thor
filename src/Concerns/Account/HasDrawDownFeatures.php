<?php

namespace Nidavellir\Thor\Concerns\Account;

use Illuminate\Support\Carbon;

trait HasDrawDownFeatures
{
    /**
     * Calculate maximum drawdowns for a given date range.
     *
     * @param  Carbon  $startDate  The start date for the range.
     * @param  Carbon  $endDate  The end date for the range.
     * @param  string  $column  The column to calculate drawdown for (e.g., total_wallet_balance).
     * @return array Maximum drawdown percentage and absolute value.
     */
    public function calculateMaxDrawdownForRange(Carbon $startDate, Carbon $endDate, $column = 'total_wallet_balance')
    {
        // Log the provided date range
        info("Starting drawdown calculation for range: {$startDate->toDateTimeString()} to {$endDate->toDateTimeString()}");

        // Fetch data for the specified date range
        $data = $this->balanceHistory()
            ->whereBetween('account_balance_history.created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->orderBy('account_balance_history.created_at')
            ->get();

        // Log the fetched data
        if ($data->isEmpty()) {
            info('No data found for the given date range.');
        } else {
            info('Fetched '.$data->count().' rows of data.');
            foreach ($data as $row) {
                info("Data: ID={$row->id}, {$column}={$row->$column}, Created At={$row->created_at}");
            }
        }

        // Calculate and return the drawdown for the dataset
        return $this->calculateMaxDrawdownForData($data, $column);
    }

    /**
     * Helper method to calculate max drawdown for a dataset.
     *
     * @param  \Illuminate\Support\Collection  $data  The collection of balance history records.
     * @param  string  $column  The column to calculate drawdown for.
     * @return array Maximum drawdown percentage and absolute values.
     */
    protected function calculateMaxDrawdownForData($data, $column)
    {
        if ($data->isEmpty()) {
            return [
                'percentage' => 0,
                'absolute' => 0,
            ];
        }

        $maxDrawdownPercentage = 0;
        $maxDrawdownAbsolute = 0;
        $peak = $data->first()->$column;

        info("Initial peak set to {$peak}");

        foreach ($data as $entry) {
            $currentValue = $entry->$column;

            if ($currentValue > $peak) {
                info("New peak from {$peak} to {$currentValue}");
                $peak = $currentValue;
            }

            $drawdownAbsolute = $peak - $currentValue;
            $drawdownPercentage = $peak > 0 ? $drawdownAbsolute / $peak : 0;

            info("Current value={$currentValue}, Drawdown Absolute={$drawdownAbsolute}, Drawdown Percentage=".($drawdownPercentage * 100).'%');

            if ($drawdownPercentage > $maxDrawdownPercentage) {
                $maxDrawdownPercentage = $drawdownPercentage;
                $maxDrawdownAbsolute = $drawdownAbsolute;
            }
        }

        info('Final Max Drawdown: Percentage='.($maxDrawdownPercentage * 100)."%, Absolute={$maxDrawdownAbsolute}");

        return [
            'percentage' => $maxDrawdownPercentage * 100, // Convert to percentage
            'absolute' => $maxDrawdownAbsolute,          // Keep absolute value
        ];
    }
}
