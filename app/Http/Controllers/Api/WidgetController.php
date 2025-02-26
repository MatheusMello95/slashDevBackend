<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use App\Models\UserWidget;
use App\Services\DiseaseService;
use App\Services\CryptoService;
use App\Services\WorldBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WidgetController extends Controller
{
    /**
     * Get all available widgets
     */
    public function index()
    {
        $widgets = Widget::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'widgets' => $widgets
        ]);
    }

    /**
     * Get user's widgets with settings
     */
    public function getUserWidgets()
    {
        $user = Auth::user();
        $dashboardSettings = $user->dashboardSettings();

        return response()->json([
            'success' => true,
            'widgets' => $dashboardSettings
        ]);
    }

    /**
     * Save a widget to user's dashboard
     */
    public function addWidgetToUser(Request $request, $widgetId)
    {
        $request->validate([
            'position' => 'sometimes|integer',
            'settings' => 'sometimes|array',
        ]);

        $user = Auth::user();
        $widget = Widget::findOrFail($widgetId);

        // Check if user already has this widget
        $userWidget = UserWidget::where('user_id', $user->id)
            ->where('widget_id', $widget->id)
            ->first();

        if ($userWidget) {
            // Update existing user widget
            $userWidget->update([
                'settings' => $request->settings ?? $widget->default_settings,
                'position' => $request->position ?? $userWidget->position,
                'is_visible' => true
            ]);
        } else {
            // Create new user widget
            $userWidget = UserWidget::create([
                'user_id' => $user->id,
                'widget_id' => $widget->id,
                'settings' => $request->settings ?? $widget->default_settings,
                'position' => $request->position ?? 0,
                'is_visible' => true
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Widget added to dashboard',
            'userWidget' => $userWidget
        ]);
    }

    /**
     * Update widget settings and position
     */
    public function updateUserWidget(Request $request, $widgetId)
    {
        $request->validate([
            'position' => 'sometimes|integer',
            'settings' => 'sometimes|array',
            'is_visible' => 'sometimes|boolean',
        ]);

        $user = Auth::user();

        $userWidget = UserWidget::where('user_id', $user->id)
            ->where('widget_id', $widgetId)
            ->firstOrFail();

        $userWidget->update($request->only(['settings', 'position', 'is_visible']));

        return response()->json([
            'success' => true,
            'message' => 'Widget updated',
            'userWidget' => $userWidget
        ]);
    }

    /**
     * Remove widget from user's dashboard
     */
    public function removeUserWidget($widgetId)
    {
        $user = Auth::user();

        $userWidget = UserWidget::where('user_id', $user->id)
            ->where('widget_id', $widgetId)
            ->firstOrFail();

        $userWidget->delete();

        return response()->json([
            'success' => true,
            'message' => 'Widget removed from dashboard'
        ]);
    }

    /**
     * Update all widget positions at once (for drag and drop reordering)
     */
    public function updateWidgetPositions(Request $request)
    {
        $request->validate([
            'positions' => 'required|array',
            'positions.*.widget_id' => 'required|integer|exists:widgets,id',
            'positions.*.position' => 'required|integer',
        ]);

        $user = Auth::user();

        foreach ($request->positions as $item) {
            UserWidget::where('user_id', $user->id)
                ->where('widget_id', $item['widget_id'])
                ->update(['position' => $item['position']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Widget positions updated'
        ]);
    }

    /**
     * Get widget data from external API
     */
    public function getWidgetData($widgetId)
    {
        $user = Auth::user();
        $widget = Widget::findOrFail($widgetId);

        // Get user settings for this widget
        $userWidget = UserWidget::where('user_id', $user->id)
            ->where('widget_id', $widgetId)
            ->first();

        $settings = $userWidget ? $userWidget->settings : $widget->default_settings;

        // Get data based on API source
        switch ($widget->api_source) {
            case 'disease':
                return $this->getDiseaseData($widget, $settings);

            case 'crypto':
                return $this->getCryptoData($widget, $settings);

            case 'worldbank':
                return $this->getWorldBankData($widget, $settings);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Unknown API source'
                ], 400);
        }
    }

    /**
     * Get disease.sh data
     */
    private function getDiseaseData($widget, $settings)
    {
        $service = new DiseaseService();

        switch ($widget->endpoint) {
            case 'global':
                $data = $service->getGlobalStats();
                break;

            case 'country':
                $country = $settings['country'] ?? 'USA';
                $data = $service->getCountryStats($country);
                break;

            case 'historical':
                $days = $settings['days'] ?? 30;
                $data = $service->getHistoricalData($days);
                break;

            default:
                $data = $service->getGlobalStats();
        }

        return response()->json([
            'success' => true,
            'widget' => $widget->only(['id', 'name', 'slug']),
            'data' => $data
        ]);
    }

    /**
     * Get CoinGecko data
     */
    private function getCryptoData($widget, $settings)
    {
        $service = new CryptoService();

        switch ($widget->endpoint) {
            case 'prices':
                $coins = $settings['coins'] ?? ['bitcoin', 'ethereum', 'litecoin'];
                $currency = $settings['currency'] ?? 'usd';
                $data = $service->getPrices($coins, $currency);
                break;

            case 'trending':
                $data = $service->getTrending();
                break;

            case 'historical':
                $coin = $settings['coin'] ?? 'bitcoin';
                $days = $settings['days'] ?? 14;
                $currency = $settings['currency'] ?? 'usd';
                $data = $service->getHistoricalData($coin, $days, $currency);
                break;

            default:
                $data = $service->getPrices();
        }

        return response()->json([
            'success' => true,
            'widget' => $widget->only(['id', 'name', 'slug']),
            'data' => $data
        ]);
    }

    /**
     * Get World Bank data
     */
    private function getWorldBankData($widget, $settings)
    {
        $service = new WorldBankService();

        switch ($widget->endpoint) {
            case 'indicators':
                $country = $settings['country'] ?? 'US';
                $indicator = $settings['indicator'] ?? 'NY.GDP.MKTP.CD'; // GDP
                $years = $settings['years'] ?? 5;
                $data = $service->getIndicators($country, $indicator, $years);
                break;

            case 'country':
                $country = $settings['country'] ?? 'US';
                $data = $service->getCountryInfo($country);
                break;

            case 'indicators_list':
                $data = $service->getIndicatorsList();
                break;

            default:
                $data = $service->getCountryInfo('US');
        }

        return response()->json([
            'success' => true,
            'widget' => $widget->only(['id', 'name', 'slug']),
            'data' => $data
        ]);
    }
}
