<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class LocaleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Set locale based on request parameter or default to 'en'
        $locale = request()->route('locale', 'en');
        
        if (in_array($locale, ['en', 'es'])) {
            App::setLocale($locale);
        } else {
            App::setLocale('en'); // Fallback to default
        }
    }
}
