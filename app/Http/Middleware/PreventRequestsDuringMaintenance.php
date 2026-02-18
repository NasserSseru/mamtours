<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/admin-bypass-token',
        '/login',
        '/api/maintenance/*',
        '/admin/maintenance/*',
        '/admin',
        '/admin/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Allow authenticated admin users to bypass maintenance mode
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
