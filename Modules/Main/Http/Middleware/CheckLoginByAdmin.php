<?php

namespace Modules\Main\Http\Middleware;

use Closure;


class CheckLoginByAdmin
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
        $module = str_replace("Controllers\Admin","Middleware",$request->route()->getAction()['namespace']);
        if(class_exists($module."\\CheckLoginByAdmin")){
            $obj = $module."\\CheckLoginByAdmin";
            return call_user_func([new $obj,"handle"],$request,$next);
        }else{
            return call_user_func([new CheckAdmin(),"handle"],$request,$next);
        }
    }
}
