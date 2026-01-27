<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSellerStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is a seller
        if (auth()->check() && auth()->user()->role === 'seller') {
            $user = auth()->user();

            // If seller is not active, redirect to status page
            if ($user->status !== 'active') {
                // Allow access only to dashboard to show status
                if (!$request->routeIs('seller.dashboard')) {
                    return redirect()->route('seller.dashboard')
                        ->with('error', 'Your account is ' . $user->status . '. Please contact admin for assistance.');
                }
            }
        }

        return $next($request);
    }
}

