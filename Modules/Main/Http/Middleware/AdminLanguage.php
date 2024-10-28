<?php

namespace Modules\Main\Http\Middleware;

use App;
use Closure;

class AdminLanguage {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!isset(session("admin_current_language")["shortcode"])) {
            // 设置，默认语言
            $array = array(
                "icon" => UPLOADPATH . '',
                "shortcode" => 'zh-CN',
                "name" => '简体中文'
            );
            session()->put("admin_current_language", $array);
        }
        //设置当前语言的目录
        App()->setLocale("admin");

        return $next($request);
    }
}
