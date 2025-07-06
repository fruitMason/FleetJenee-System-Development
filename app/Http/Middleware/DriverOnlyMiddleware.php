<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DriverOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            Log::info('Driver Middleware');

            if ($user?->type === 'ADMINISTRATOR') {
                return redirect(route('dashboard'));
            }
            if ($user?->type === 'DRIVER') {
                return $next($request);
            }
            if ($user?->type === 'MECHANIC') {
                return redirect(route('mechanic.dashboard'));
            }
            if ($user?->type === 'ACCOUNT') {
                return redirect(route('account.dashboard')); //
            }
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
