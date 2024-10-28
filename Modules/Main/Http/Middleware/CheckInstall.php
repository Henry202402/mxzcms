<?php

namespace Modules\Main\Http\Middleware;


use Closure;
use Modules\Install\Http\Controllers\InstallController;

class CheckInstall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!InstallController::checkInstall()) return redirect('/install');
        return $next($request);
    }
}
