<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class CryptoService
{
    protected $baseUrl = 'https://api.coingecko.com/api/v3';

    /**
     * Get cryptocurrency prices for specific coins
     */
    public function getPrices($coins = ['bitcoin', 'ethereum', 'litecoin'], $currency = 'usd')
    {
        try {
            $response = Http::get("{$this->baseUrl}/simple/price", [
                'ids' => implode(',', $coins),
                'vs_currencies' => $currency,
                'include_24hr_change' => 'true',
                'include_market_cap' => 'true'
            ]);
            return $response->json();
        } catch (RequestException $e) {
            Log::error('Error fetching crypto prices: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get trending cryptocurrencies
     */
    public function getTrending()
    {
        try {
            $response = Http::get("{$this->baseUrl}/search/trending");
            return $response->json();
        } catch (RequestException $e) {
            Log::error('Error fetching trending crypto: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get historical market data for a coin
     */
    public function getHistoricalData($coin, $days = 14, $currency = 'usd')
    {
        try {
            $response = Http::get("{$this->baseUrl}/coins/{$coin}/market_chart", [
                'vs_currency' => $currency,
                'days' => $days
            ]);
            return $response->json();
        } catch (RequestException $e) {
            Log::error("Error fetching historical data for {$coin}: " . $e->getMessage());
            return null;
        }
    }
}
