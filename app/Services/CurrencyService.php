<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    public function getRates()
    {
        return Cache::remember('exchange_rates', 3600, function () {
            $response = Http::get("http://data.fixer.io/api/latest", [
                'access_key' => env('FIXER_API_KEY'),
                'symbols' => 'NGN,USD,GBP',
            ]);

            if ($response->successful() && $response['success']) {
                return $response['rates'];
            }

            return [
                'NGN' => 1,
                'USD' => 0.00065,
                'GBP' => 0.00048,
            ]; // fallback
        });
    }

    public function convert($amountInNGN, $currency)
    {
        $rates = $this->getRates();

        // Fixer only supports EUR as base in free plan, so convert NGN -> EUR -> currency
        $ngnToEur = 1 / $rates['NGN'];
        $converted = $amountInNGN * $ngnToEur * $rates[$currency];

        $symbol = [
            'NGN' => '₦ ',
            'USD' => '$ ',
            'GBP' => '£ ',
        ][$currency] ?? '₦ ';

        return $symbol . number_format($converted, 2);
    }
}
