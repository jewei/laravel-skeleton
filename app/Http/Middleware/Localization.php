<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if user is logged in, get the locale from the user settings
        if (app('auth')->check()) {
            app()->setLocale(app('auth')->user()->locale ?? config('app.locale'));

            return $next($request);
        }

        // if user is not logged in, get the locale from the session
        app()->setLocale(session()->get('locale', config('app.locale')));

        return $next($request);
    }
}
