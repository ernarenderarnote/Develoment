<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Components\Logger;

/*
 * Use for debugging purpose
 */
class Debug
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //if (auth()->user()) {
        //    header('X-MSS-USER-ID: '.auth()->user()->id);
        //}
        //header('X-MSS-USER: '. json_encode(auth()->user()));
        //header('X-MSS-SESSION-ID: '.Auth::guard($guard)->getSession()->getId());

        if (filter_var(getenv('APP_LOG_REQUESTS'), FILTER_VALIDATE_BOOLEAN)) {
            Logger::i(Logger::REQUESTS)->info(request()->url(), [
                'url' => request()->fullUrl(),
                'ip' => request()->ip(),
                'request' => request()->all()
            ]);
        }

        return $next($request);
    }
}
