<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FleetManagerOnlyMiddleware
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
            //  Log::info($user);

            if ($user?->type === 'ADMINISTRATOR') {
                // Log::info('next - fleet');
                return $next($request);
            }
            if ($user?->type === 'DRIVER') {
                return redirect(route('driver.dashboard'));
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
