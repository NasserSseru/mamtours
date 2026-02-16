<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AnalyticsService
{
    /**
     * Track an analytics event
     */
    public function track(string $eventType, string $eventName, array $properties = []): void
    {
        try {
            DB::table('analytics_events')->insert([
                'event_type' => $eventType,
                'event_name' => $eventName,
                'user_id' => Auth::id(),
                'properties' => json_encode($properties),
                'session_id' => session()->getId(),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'referrer' => Request::header('referer'),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the request
            logger()->error('Analytics tracking failed', [
                'event_type' => $eventType,
                'event_name' => $eventName,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Track page view
     */
    public function trackPageView(string $page, array $properties = []): void
    {
        $this->track('page_view', $page, $properties);
    }

    /**
     * Track car view
     */
    public function trackCarView(int $carId, array $carData = []): void
    {
        $this->track('car_view', 'Car Viewed', array_merge([
            'car_id' => $carId,
        ], $carData));
    }

    /**
     * Track booking creation
     */
    public function trackBookingCreated(int $bookingId, array $bookingData = []): void
    {
        $this->track('booking', 'Booking Created', array_merge([
            'booking_id' => $bookingId,
        ], $bookingData));
    }

    /**
     * Track booking completion
     */
    public function trackBookingCompleted(int $bookingId, float $amount): void
    {
        $this->track('booking', 'Booking Completed', [
            'booking_id' => $bookingId,
            'amount' => $amount,
        ]);
    }

    /**
     * Track search
     */
    public function trackSearch(string $query, int $resultsCount): void
    {
        $this->track('search', 'Search Performed', [
            'query' => $query,
            'results_count' => $resultsCount,
        ]);
    }

    /**
     * Get analytics summary for a date range
     */
    public function getSummary(string $startDate, string $endDate): array
    {
        $events = DB::table('analytics_events')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('event_type', DB::raw('count(*) as count'))
            ->groupBy('event_type')
            ->get();

        return [
            'total_events' => $events->sum('count'),
            'by_type' => $events->pluck('count', 'event_type')->toArray(),
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ];
    }

    /**
     * Get popular cars based on views
     */
    public function getPopularCars(int $limit = 10): array
    {
        return DB::table('analytics_events')
            ->where('event_type', 'car_view')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('JSON_EXTRACT(properties, "$.car_id") as car_id'), DB::raw('count(*) as views'))
            ->groupBy('car_id')
            ->orderByDesc('views')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
