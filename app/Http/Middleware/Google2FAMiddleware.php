<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Google2FAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->google2fa_enable && !$request->session()->get('2fa_verified', false)) {
            return redirect()->route('2fa.index');
        }

        return $next($request);
    }
}
