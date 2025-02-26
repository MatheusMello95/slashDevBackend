<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class DiseaseService
{
    protected $baseUrl = 'https://disease.sh/v3/covid-19';

    /**
     * Get global COVID-19 statistics
     */
    public function getGlobalStats()
    {
        try {
            $response = Http::get("{$this->baseUrl}/all");
            return $response->json();
        } catch (RequestException $e) {
            Log::error('Error fetching COVID-19 global stats: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get COVID-19 statistics by country
     */
    public function getCountryStats($country)
    {
        try {
            $response = Http::get("{$this->baseUrl}/countries/{$country}");
            return $response->json();
        } catch (RequestException $e) {
            Log::error("Error fetching COVID-19 stats for {$country}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get COVID-19 historical data
     */
    public function getHistoricalData($days = 30)
    {
        try {
            $response = Http::get("{$this->baseUrl}/historical/all", [
                'lastdays' => $days
            ]);
            return $response->json();
        } catch (RequestException $e) {
            Log::error('Error fetching COVID-19 historical data: ' . $e->getMessage());
            return null;
        }
    }
}




