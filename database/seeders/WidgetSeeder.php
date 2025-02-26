<?php

namespace Database\Seeders;

use App\Models\Widget;
use Illuminate\Database\Seeder;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // COVID-19 Widgets
        Widget::create([
            'name' => 'Global COVID-19 Stats',
            'slug' => 'covid-global',
            'api_source' => 'disease',
            'endpoint' => 'global',
            'description' => 'Shows current global COVID-19 statistics',
            'default_settings' => json_encode([]),
            'is_active' => true,
        ]);

        Widget::create([
            'name' => 'COVID-19 By Country',
            'slug' => 'covid-country',
            'api_source' => 'disease',
            'endpoint' => 'country',
            'description' => 'Shows COVID-19 statistics for a specific country',
            'default_settings' => json_encode([
                'country' => 'USA'
            ]),
            'is_active' => true,
        ]);

        Widget::create([
            'name' => 'COVID-19 Historical Trends',
            'slug' => 'covid-historical',
            'api_source' => 'disease',
            'endpoint' => 'historical',
            'description' => 'Shows historical COVID-19 trends',
            'default_settings' => json_encode([
                'days' => 30
            ]),
            'is_active' => true,
        ]);

        // Cryptocurrency Widgets
        Widget::create([
            'name' => 'Cryptocurrency Prices',
            'slug' => 'crypto-prices',
            'api_source' => 'crypto',
            'endpoint' => 'prices',
            'description' => 'Shows current prices for popular cryptocurrencies',
            'default_settings' => json_encode([
                'coins' => ['bitcoin', 'ethereum', 'litecoin'],
                'currency' => 'usd'
            ]),
            'is_active' => true,
        ]);

        Widget::create([
            'name' => 'Trending Cryptocurrencies',
            'slug' => 'crypto-trending',
            'api_source' => 'crypto',
            'endpoint' => 'trending',
            'description' => 'Shows trending cryptocurrencies',
            'default_settings' => json_encode([]),
            'is_active' => true,
        ]);

        Widget::create([
            'name' => 'Cryptocurrency Price Chart',
            'slug' => 'crypto-chart',
            'api_source' => 'crypto',
            'endpoint' => 'historical',
            'description' => 'Shows historical price chart for a cryptocurrency',
            'default_settings' => json_encode([
                'coin' => 'bitcoin',
                'days' => 14,
                'currency' => 'usd'
            ]),
            'is_active' => true,
        ]);

        // World Bank Widgets
        Widget::create([
            'name' => 'Country GDP',
            'slug' => 'country-gdp',
            'api_source' => 'worldbank',
            'endpoint' => 'indicators',
            'description' => 'Shows GDP data for a country',
            'default_settings' => json_encode([
                'country' => 'US',
                'indicator' => 'NY.GDP.MKTP.CD',
                'years' => 5
            ]),
            'is_active' => true,
        ]);

        Widget::create([
            'name' => 'Country Information',
            'slug' => 'country-info',
            'api_source' => 'worldbank',
            'endpoint' => 'country',
            'description' => 'Shows general information about a country',
            'default_settings' => json_encode([
                'country' => 'US'
            ]),
            'is_active' => true,
        ]);

        Widget::create([
            'name' => 'Economic Indicators',
            'slug' => 'economic-indicators',
            'api_source' => 'worldbank',
            'endpoint' => 'indicators_list',
            'description' => 'Shows available economic indicators',
            'default_settings' => json_encode([]),
            'is_active' => true,
        ]);
    }
}
