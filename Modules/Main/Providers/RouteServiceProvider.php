<?php

namespace Modules\Main\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Modules\Main\Http\Middleware\Cors;
use Mxzcms\Modules\Middleware\CheckInstall;

class RouteServiceProvider extends ServiceProvider
{


    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {

        $this->routes(function () {
            Route::middleware('web')
                ->prefix('admin')
                ->namespace("Modules\Main\Http\Controllers")
                ->group(module_path("Main",'Routes/admin.php'));

            Route::middleware(['api',Cors::class])
                ->prefix('api')
                ->namespace("Modules\Main\Http\Controllers")
                ->group(module_path('Main','Routes/api.php'));

            Route::middleware(['web',CheckInstall::class])
                ->namespace("Modules\Main\Http\Controllers")
                ->group(module_path('Main','Routes/web.php'));
        });
    }
}
