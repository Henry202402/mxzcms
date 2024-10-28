<?php

namespace Modules\Main\Http\Middleware;

use Closure;

class SeoDomainLimit {

    public function handle($request, Closure $next) {

        return $next($request);
    }
}