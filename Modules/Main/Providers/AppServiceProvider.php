<?php

namespace Modules\Main\Providers;

use Illuminate\Support\ServiceProvider;
use Mews\Captcha\CaptchaServiceProvider;
use Modules\Main\Libs\CMSBOOSTRAP;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        CMSBOOSTRAP::boostrap();
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(CaptchaServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        CMSBOOSTRAP::checkEnvValue();
    }



}
