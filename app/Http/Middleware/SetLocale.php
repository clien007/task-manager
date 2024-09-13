<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Extract locale from the URL segment
        $locale = $request->segment(1);

        // Validate the locale
        if (in_array($locale, ['en', 'es'])) {
            // Set the application locale
            App::setLocale($locale);
        } else {
            // Default to English if locale is invalid
            App::setLocale('en');
        }

        return $next($request);
    }
}
