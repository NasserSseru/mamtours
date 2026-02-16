<?php

namespace App\Services;

use App\Models\PageVisit;
use App\Models\UserAction;
use App\Models\UserSession;
use App\Models\DailyAnalytic;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VisitorTrackingService
{
    /**
     * Track a page visit
     */
    public function trackPageVisit(Request $request, $pageTitle = null, $durationSeconds = 0)
    {
        try {
            PageVisit::create([
                'page_url' => $request->path(),
                'page_title' => $pageTitle,
                'referrer' => $request->header('referer'),
                'user_agent' => $request->header('user-agent'),
                'ip_address' => $this->getClientIp($request),
                'user_id' => auth()->id(),
                'duration_seconds' => $durationSeconds,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to track page visit: ' . $e->getMessage());
        }
    }

    /**
     * Track user action
     */
    public function trackAction(Request $request, $actionType, $actionName, $resourceType = null, $resourceId = null, $metadata = null)
    {
        try {
            UserAction::create([
                'user_id' => auth()->id(),
                'action_type' => $actionType,
                'action_name' => $actionName,
                'resource_type' => $resourceType,
                'resource_id' => $resourceId,
                'metadata' => $metadata,
                'ip_address' => $this->getClientIp($request),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to track action: ' . $e->getMessage());
        }
    }

    /**
     * Track user session
     */
    public function trackSession(Request $request)
    {
        try {
            $sessionId = session()->getId();
            $userAgent = $request->header('user-agent');
            
            $session = UserSession::where('session_id', $sessionId)->first();
            
            if (!$session) {
                $session = UserSession::create([
                    'user_id' => auth()->id(),
                    'session_id' => $sessionId,
                    'ip_address' => $this->getClientIp($request),
                    'user_agent' => $userAgent,
                    'device_type' => $this->getDeviceType($userAgent),
                    'browser' => $this->getBrowser($userAgent),
                    'os' => $this->getOS($userAgent),
                    'last_activity_at' => now(),
                ]);
            } else {
                $session->update([
                    'user_id' => auth()->id(),
                    'last_activity_at' => now(),
                ]);
            }
            
            return $session;
        } catch (\Exception $e) {
            \Log::error('Failed to track session: ' . $e->getMessage());
        }
    }

    /**
     * Get analytics dashboard data
     */
    public function getDashboardData($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'total_visitors' => PageVisit::where('created_at', '>=', $startDate)->distinct('ip_address')->count(),
            'unique_users' => PageVisit::where('created_at', '>=', $startDate)->whereNotNull('user_id')->distinct('user_id')->count(),
            'total_page_views' => PageVisit::where('created_at', '>=', $startDate)->count(),
            'new_users' => \App\Models\User::where('created_at', '>=', $startDate)->count(),
            'total_bookings' => \App\Models\Booking::where('created_at', '>=', $startDate)->count(),
            'completed_bookings' => \App\Models\Booking::where('created_at', '>=', $startDate)->where('status', 'completed')->count(),
            'pending_bookings' => \App\Models\Booking::where('created_at', '>=', $startDate)->where('status', 'pending')->count(),
            'confirmed_bookings' => \App\Models\Booking::where('created_at', '>=', $startDate)->where('status', 'confirmed')->count(),
            'total_booking_value' => \App\Models\Booking::where('created_at', '>=', $startDate)->sum('totalPrice'),
            'avg_session_duration' => PageVisit::where('created_at', '>=', $startDate)->avg('duration_seconds'),
            'top_pages' => $this->getTopPages($days),
            'traffic_by_device' => $this->getTrafficByDevice($days),
            'traffic_by_browser' => $this->getTrafficByBrowser($days),
            'daily_visitors' => $this->getDailyVisitors($days),
            'daily_bookings' => $this->getDailyBookings($days),
        ];
    }

    /**
     * Get top pages
     */
    public function getTopPages($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return PageVisit::where('created_at', '>=', $startDate)
            ->groupBy('page_url', 'page_title')
            ->selectRaw('page_url, page_title, COUNT(*) as visits')
            ->orderByDesc('visits')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Get traffic by device
     */
    public function getTrafficByDevice($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return UserSession::where('created_at', '>=', $startDate)
            ->groupBy('device_type')
            ->selectRaw('device_type, COUNT(*) as count')
            ->get()
            ->pluck('count', 'device_type')
            ->toArray();
    }

    /**
     * Get traffic by browser
     */
    public function getTrafficByBrowser($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return UserSession::where('created_at', '>=', $startDate)
            ->groupBy('browser')
            ->selectRaw('browser, COUNT(*) as count')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->pluck('count', 'browser')
            ->toArray();
    }

    /**
     * Get daily visitors
     */
    public function getDailyVisitors($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return PageVisit::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT ip_address) as visitors')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('visitors', 'date')
            ->toArray();
    }

    /**
     * Get daily bookings
     */
    public function getDailyBookings($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return \App\Models\Booking::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as bookings, SUM(totalPrice) as value')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'bookings' => $item->bookings,
                    'value' => $item->value,
                ];
            })
            ->toArray();
    }

    /**
     * Get user activity
     */
    public function getUserActivity($userId, $limit = 50)
    {
        return UserAction::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get active users
     */
    public function getActiveUsers($minutes = 30)
    {
        $startTime = Carbon::now()->subMinutes($minutes);
        
        return UserSession::where('last_activity_at', '>=', $startTime)
            ->with('user')
            ->orderByDesc('last_activity_at')
            ->get();
    }

    /**
     * Get user details
     */
    public function getUserDetails($userId)
    {
        $user = \App\Models\User::find($userId);
        
        if (!$user) {
            return null;
        }
        
        return [
            'user' => $user,
            'total_visits' => PageVisit::where('user_id', $userId)->count(),
            'total_actions' => UserAction::where('user_id', $userId)->count(),
            'total_bookings' => \App\Models\Booking::where('user_id', $userId)->count(),
            'total_spent' => \App\Models\Booking::where('user_id', $userId)->sum('totalPrice'),
            'last_visit' => PageVisit::where('user_id', $userId)->latest()->first(),
            'recent_actions' => UserAction::where('user_id', $userId)->latest()->limit(10)->get(),
            'bookings' => \App\Models\Booking::where('user_id', $userId)->latest()->limit(5)->get(),
        ];
    }

    /**
     * Helper: Get client IP
     */
    private function getClientIp($request)
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Helper: Get device type
     */
    private function getDeviceType($userAgent)
    {
        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Helper: Get browser
     */
    private function getBrowser($userAgent)
    {
        if (preg_match('/firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/chrome/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/edge/i', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            return 'Opera';
        }
        return 'Other';
    }

    /**
     * Helper: Get OS
     */
    private function getOS($userAgent)
    {
        if (preg_match('/windows/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/mac/i', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            return 'iOS';
        }
        return 'Other';
    }
}
