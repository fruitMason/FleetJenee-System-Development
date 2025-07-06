<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MechanicOnlyMiddleware
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
            Log::info('Mechanic Middleware');

            if ($user?->type === 'ADMINISTRATOR') {
                return redirect(route('dashboard'));
            }
            if ($user?->type === 'DRIVER') {
                  return redirect(route('driver.dashboard'));
            }
            if ($user?->type === 'MECHANIC') {             
                 return $next($request);
            }
            if ($user?->type === 'ACCOUNT') {
                return redirect(route('account.dashboard')); //
            }
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
