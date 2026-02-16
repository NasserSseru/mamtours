<?php

namespace App\Http\Controllers;

use App\Services\VisitorTrackingService;
use App\Models\PageVisit;
use App\Models\UserAction;
use App\Models\UserSession;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $trackingService;

    public function __construct(VisitorTrackingService $trackingService)
    {
        $this->middleware('admin');
        $this->trackingService = $trackingService;
    }

    /**
     * Show analytics dashboard
     */
    public function dashboard(Request $request)
    {
        $days = $request->query('days', 30);
        $data = $this->trackingService->getDashboardData($days);
        
        return view('admin.analytics.dashboard', $data);
    }

    /**
     * Get analytics data as JSON
     */
    public function data(Request $request)
    {
        $days = $request->query('days', 30);
        $data = $this->trackingService->getDashboardData($days);
        
        return response()->json($data);
    }

    /**
     * Show visitors page
     */
    public function visitors(Request $request)
    {
        $days = $request->query('days', 7);
        $startDate = \Carbon\Carbon::now()->subDays($days);
        
        $visitors = PageVisit::where('created_at', '>=', $startDate)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(50);
        
        $stats = [
            'total_visits' => PageVisit::where('created_at', '>=', $startDate)->count(),
            'unique_visitors' => PageVisit::where('created_at', '>=', $startDate)->distinct('ip_address')->count(),
            'unique_users' => PageVisit::where('created_at', '>=', $startDate)->whereNotNull('user_id')->distinct('user_id')->count(),
        ];
        
        return view('admin.analytics.visitors', compact('visitors', 'stats', 'days'));
    }

    /**
     * Show users page
     */
    public function users(Request $request)
    {
        $search = $request->query('search');
        $sort = $request->query('sort', 'created_at');
        $order = $request->query('order', 'desc');
        
        $query = User::query();
        
        if ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%");
        }
        
        $users = $query->orderBy($sort, $order)
            ->paginate(50);
        
        return view('admin.analytics.users', compact('users', 'search', 'sort', 'order'));
    }

    /**
     * Show user details
     */
    public function userDetails($userId)
    {
        $details = $this->trackingService->getUserDetails($userId);
        
        if (!$details) {
            return redirect()->route('admin.analytics.users')->with('error', 'User not found');
        }
        
        return view('admin.analytics.user-details', $details);
    }

    /**
     * Show active users
     */
    public function activeUsers(Request $request)
    {
        $minutes = $request->query('minutes', 30);
        $activeUsers = $this->trackingService->getActiveUsers($minutes);
        
        return view('admin.analytics.active-users', compact('activeUsers', 'minutes'));
    }

    /**
     * Show page analytics
     */
    public function pages(Request $request)
    {
        $days = $request->query('days', 30);
        $startDate = \Carbon\Carbon::now()->subDays($days);
        
        $pages = PageVisit::where('created_at', '>=', $startDate)
            ->selectRaw('page_url, page_title, COUNT(*) as visits, AVG(duration_seconds) as avg_duration')
            ->groupBy('page_url', 'page_title')
            ->orderByDesc('visits')
            ->paginate(50);
        
        return view('admin.analytics.pages', compact('pages', 'days'));
    }

    /**
     * Show user actions
     */
    public function actions(Request $request)
    {
        $days = $request->query('days', 7);
        $actionType = $request->query('type');
        $startDate = \Carbon\Carbon::now()->subDays($days);
        
        $query = UserAction::where('created_at', '>=', $startDate);
        
        if ($actionType) {
            $query->where('action_type', $actionType);
        }
        
        $actions = $query->with('user')
            ->orderByDesc('created_at')
            ->paginate(50);
        
        $actionTypes = UserAction::distinct('action_type')->pluck('action_type');
        
        return view('admin.analytics.actions', compact('actions', 'actionTypes', 'actionType', 'days'));
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $type = $request->query('type', 'visitors');
        $days = $request->query('days', 30);
        $startDate = \Carbon\Carbon::now()->subDays($days);
        
        $filename = "analytics-{$type}-" . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function () use ($type, $startDate) {
            $file = fopen('php://output', 'w');
            
            if ($type === 'visitors') {
                fputcsv($file, ['Date', 'Page', 'User', 'IP', 'Duration (s)']);
                
                PageVisit::where('created_at', '>=', $startDate)
                    ->with('user')
                    ->orderByDesc('created_at')
                    ->chunk(100, function ($visits) use ($file) {
                        foreach ($visits as $visit) {
                            fputcsv($file, [
                                $visit->created_at->format('Y-m-d H:i:s'),
                                $visit->page_url,
                                $visit->user?->name ?? 'Guest',
                                $visit->ip_address,
                                $visit->duration_seconds,
                            ]);
                        }
                    });
            } elseif ($type === 'users') {
                fputcsv($file, ['Name', 'Email', 'Phone', 'Role', 'Joined', 'Last Login']);
                
                User::where('created_at', '>=', $startDate)
                    ->orderByDesc('created_at')
                    ->chunk(100, function ($users) use ($file) {
                        foreach ($users as $user) {
                            fputcsv($file, [
                                $user->name,
                                $user->email,
                                $user->phone,
                                $user->role,
                                $user->created_at->format('Y-m-d H:i:s'),
                                $user->last_login_at?->format('Y-m-d H:i:s') ?? 'Never',
                            ]);
                        }
                    });
            } elseif ($type === 'bookings') {
                fputcsv($file, ['Booking ID', 'Customer', 'Vehicle', 'Start Date', 'End Date', 'Status', 'Total Price']);
                
                Booking::where('created_at', '>=', $startDate)
                    ->with('car')
                    ->orderByDesc('created_at')
                    ->chunk(100, function ($bookings) use ($file) {
                        foreach ($bookings as $booking) {
                            fputcsv($file, [
                                $booking->id,
                                $booking->customerName,
                                $booking->car?->brand . ' ' . $booking->car?->model,
                                $booking->startDate,
                                $booking->endDate,
                                $booking->status,
                                $booking->totalPrice,
                            ]);
                        }
                    });
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get API data for charts
     */
    public function chartData(Request $request)
    {
        $type = $request->query('type', 'daily_visitors');
        $days = $request->query('days', 30);
        
        $data = match($type) {
            'daily_visitors' => $this->trackingService->getDailyVisitors($days),
            'daily_bookings' => $this->trackingService->getDailyBookings($days),
            'traffic_device' => $this->trackingService->getTrafficByDevice($days),
            'traffic_browser' => $this->trackingService->getTrafficByBrowser($days),
            'top_pages' => $this->trackingService->getTopPages($days),
            default => [],
        };
        
        return response()->json($data);
    }
}
