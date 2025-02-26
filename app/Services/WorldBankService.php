<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class WorldBankService
{
    protected $baseUrl = 'https://api.worldbank.org/v2';

    /**
     * Get economic indicators for a country
     */
    public function getIndicators($country, $indicator, $years = 5)
    {
        try {
            $response = Http::get("{$this->baseUrl}/country/{$country}/indicator/{$indicator}", [
                'format' => 'json',
                'per_page' => $years,
                'date' => date('Y') - $years . ':' . date('Y')
            ]);
            return $response->json();
        } catch (RequestException $e) {
            Log::error("Error fetching indicator {$indicator} for {$country}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get country information
     */
    public function getCountryInfo($country)
    {
        try {
            $response = Http::get("{$this->baseUrl}/country/{$country}", [
                'format' => 'json'
            ]);
            return $response->json();
        } catch (RequestException $e) {
            Log::error("Error fetching info for {$country}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get economic indicators list
     */
    public function getIndicatorsList()
    {
        try {
            $response = Http::get("{$this->baseUrl}/indicators", [
                'format' => 'json',
                'per_page' => 50
            ]);
            return $response->json();
        } catch (RequestException $e) {
            Log::error("Error fetching indicators list: " . $e->getMessage());
            return null;
        }
    }
}
