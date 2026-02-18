<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MaintenanceController extends Controller
{
    /**
     * Check if maintenance mode is active
     */
    public function status()
    {
        $isDown = File::exists(storage_path('framework/down'));
        
        return response()->json([
            'maintenance_mode' => $isDown,
            'status' => $isDown ? 'down' : 'up'
        ]);
    }

    /**
     * Enable maintenance mode
     */
    public function enable(Request $request)
    {
        try {
            // Enable maintenance mode with a secret token for admin access
            // Admins can bypass by visiting: /admin-bypass-token
            Artisan::call('down', [
                '--secret' => 'admin-bypass-token',
                '--render' => 'errors::503',
                '--refresh' => 15,
            ]);

            return response()->json([
                'message' => 'Maintenance mode enabled successfully. Regular users will see maintenance page.',
                'maintenance_mode' => true,
                'admin_bypass_url' => url('/admin-bypass-token'),
                'note' => 'Admins can access the site by visiting the bypass URL once, then browsing normally.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to enable maintenance mode',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disable maintenance mode
     */
    public function disable()
    {
        try {
            Artisan::call('up');

            return response()->json([
                'message' => 'Maintenance mode disabled successfully',
                'maintenance_mode' => false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to disable maintenance mode',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
