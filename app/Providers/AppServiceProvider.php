<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TaskOwnershipMiddleware;
use App\Http\Middleware\ArchiveOwnershipMiddleware;
use App\Http\Middleware\SetLocale;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('task.ownership', TaskOwnershipMiddleware::class);
        Route::aliasMiddleware('archive.ownership', ArchiveOwnershipMiddleware::class);
        Route::aliasMiddleware('setlocale', SetLocale::class);
        Route::prefix('api')
                ->middleware('api')
                ->namespace($this->app->getNamespace())
                ->group(base_path('routes/api.php'));
    
            Route::middleware('web')
                ->namespace($this->app->getNamespace())
                ->group(base_path('routes/web.php'));
    }
}
