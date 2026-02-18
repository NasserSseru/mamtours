<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Debug route to check database columns
Route::get('/debug/car-columns', function() {
    $car = \App\Models\Car::first();
    if ($car) {
        return response()->json([
            'columns' => array_keys($car->toArray()),
            'sample_data' => $car->toArray()
        ]);
    }
    return response()->json(['error' => 'No cars found']);
});

// Authentication API
Route::post('/auth/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    if ($user->isLocked()) {
        return response()->json([
            'message' => 'Account is temporarily locked'
        ], 423);
    }

    $token = $user->createApiToken('api-access');
    $user->updateLoginInfo($request->ip());

    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]
    ]);
});

Route::middleware('auth:sanctum')->post('/auth/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out successfully']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    $kyc = $user->kyc;
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'phone' => $user->phone,
        'role' => $user->role,
        'kyc_verified' => $user->isKycVerified(),
        'kyc_status' => $kyc ? $kyc->status : 'not_submitted',
    ]);
});

// Health check
Route::get('/health', function() {
    return response()->json([
        'status' => 'ok',
        'message' => 'MAM Tours API is running',
        'timestamp' => now(),
        'cars_count' => \App\Models\Car::count(),
        'bookings_count' => \App\Models\Booking::count(),
    ]);
});

// Images
Route::get('/images', [CarController::class, 'getImages']);

// Public Cars API (read-only)
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{id}', [CarController::class, 'show']);

// Maintenance Mode API (admin only)
Route::middleware(['auth:sanctum,web', 'admin'])->group(function () {
    Route::get('/maintenance/status', [\App\Http\Controllers\MaintenanceController::class, 'status']);
    Route::post('/maintenance/enable', [\App\Http\Controllers\MaintenanceController::class, 'enable']);
    Route::post('/maintenance/disable', [\App\Http\Controllers\MaintenanceController::class, 'disable']);
});

// Protected Cars API (admin only) - uses web session auth
Route::middleware(['web', 'auth', 'admin'])->group(function () {
    Route::post('/cars', [CarController::class, 'store']);
    Route::put('/cars/{id}', [CarController::class, 'update']);
    Route::delete('/cars/{id}', [CarController::class, 'destroy']);
});

// Protected Bookings API (authenticated users) - uses web session auth
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookings/{id}', [BookingController::class, 'show']);
    Route::post('/bookings/reserve', [BookingController::class, 'reserve']);
    Route::post('/bookings/{id}/confirm', [BookingController::class, 'confirm']);
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
    
    // Admin-only booking operations
    Route::middleware('admin')->group(function () {
        Route::post('/bookings/{id}/check-out', [BookingController::class, 'checkout']);
        Route::put('/bookings/{id}/return', [BookingController::class, 'returnVehicle']);
    });
});

// Public Bookings API (unauthenticated users can submit bookings)
Route::post('/bookings', [BookingController::class, 'store']);

// Payment stubs (for compatibility)
Route::post('/payments/intent', function(Request $request) {
    return response()->json(['message' => 'Payment authorized', 'status' => 'authorized']);
});
Route::post('/payments/capture/{id}', function($id) {
    return response()->json(['message' => 'Payment captured']);
});
Route::post('/payments/refund/{id}', function($id) {
    return response()->json(['message' => 'Payment refunded']);
});

// Protected Reports (admin only)
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/reports/bookings.csv', function() {
        $bookings = \App\Models\Booking::with('car')->get();
        $csv = "id,carId,customerName,startDate,endDate,status,total,createdAt\n";
        foreach($bookings as $booking) {
            $total = $booking->pricing['total'] ?? '';
            $csv .= implode(',', [
                $booking->id,
                $booking->car_id,
                '"' . str_replace('"', '""', $booking->customerName) . '"',
                $booking->startDate,
                $booking->endDate,
                $booking->status,
                $total,
                $booking->created_at
            ]) . "\n";
        }
        return response($csv)->header('Content-Type', 'text/csv');
    });

    Route::get('/reports/fleet.csv', function() {
        $cars = \App\Models\Car::all();
        $csv = "id,brand,model,numberPlate,dailyRate,seats,category,isAvailable\n";
        foreach($cars as $car) {
            $csv .= implode(',', [
                $car->id,
                '"' . str_replace('"', '""', $car->brand) . '"',
                '"' . str_replace('"', '""', $car->model) . '"',
                $car->numberPlate,
                $car->dailyRate,
                $car->seats,
                $car->category ?? '',
                $car->isAvailable ? 1 : 0
            ]) . "\n";
        }
        return response($csv)->header('Content-Type', 'text/csv');
    });
});

// API v1 Routes
Route::prefix('v1')->middleware('api.version:v1')->group(function () {
    Route::get('/cars', [\App\Http\Controllers\Api\V1\CarApiController::class, 'index']);
    Route::get('/cars/{id}', [\App\Http\Controllers\Api\V1\CarApiController::class, 'show']);
    Route::get('/cars/search', [\App\Http\Controllers\Api\V1\CarApiController::class, 'search']);
});

// API v2 Routes (Enhanced with more features)
Route::prefix('v2')->middleware('api.version:v2')->group(function () {
    Route::get('/cars', [\App\Http\Controllers\Api\V2\CarApiController::class, 'index']);
    Route::get('/cars/{id}', [\App\Http\Controllers\Api\V2\CarApiController::class, 'show']);
    Route::get('/cars/search', [\App\Http\Controllers\Api\V2\CarApiController::class, 'search']);
});

// Webhook Routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/webhooks/register', [\App\Http\Controllers\WebhookController::class, 'register']);
    Route::get('/webhooks/deliveries/{id}', [\App\Http\Controllers\WebhookController::class, 'status']);
});

Route::post('/webhooks/receive', [\App\Http\Controllers\WebhookController::class, 'receive']);

// API Documentation
Route::get('/docs/spec', [\App\Http\Controllers\ApiDocController::class, 'spec']);

// Test Upload Endpoint (for debugging)
Route::post('/test-upload', function (Request $request) {
    try {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('test-uploads', 'public');
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file' => [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'path' => $path,
                    'url' => asset('storage/' . $path)
                ]
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'No file provided'], 400);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

// KYC API v2 Routes
Route::prefix('v2')->middleware('api.version:v2')->group(function () {
    // User KYC endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/kyc/status', [\App\Http\Controllers\Api\V2\KycApiController::class, 'status']);
        Route::post('/kyc/submit', [\App\Http\Controllers\Api\V2\KycApiController::class, 'submit']);
        Route::get('/kyc/audit-trail', [\App\Http\Controllers\Api\V2\KycApiController::class, 'auditTrail']);
    });

    // Admin KYC endpoints
    Route::middleware(['auth:sanctum', 'admin'])->prefix('admin/kyc')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\V2\KycApiController::class, 'adminList']);
        Route::get('/statistics', [\App\Http\Controllers\Api\V2\KycApiController::class, 'adminStatistics']);
        Route::post('/{id}/verify', [\App\Http\Controllers\Api\V2\KycApiController::class, 'adminVerify']);
        Route::post('/{id}/reject', [\App\Http\Controllers\Api\V2\KycApiController::class, 'adminReject']);
        Route::post('/{id}/request-additional', [\App\Http\Controllers\Api\V2\KycApiController::class, 'adminRequestAdditional']);
        Route::get('/{id}/document/{type}', [\App\Http\Controllers\Api\V2\KycApiController::class, 'adminViewDocument']);
        Route::get('/{id}/audit-trail', [\App\Http\Controllers\Api\V2\KycApiController::class, 'adminAuditTrail']);
    });
});
