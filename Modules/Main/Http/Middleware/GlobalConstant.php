<?php

namespace Modules\Main\Http\Middleware;

use Closure;

class GlobalConstant
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
        $request->merge(['requestid' => uniqid().rand(10000,99999).rand(1000,9999)]);
        //主题路径
        if(!cache('theme')){
            cache('theme','default');
        }
        define("HOME_SKIN_PATH","views/themes/".cache('theme')."/"); //带有主题标识的相对路径
        define("INSTALL_SKIN_PATH","views/install/");
        define("THEME_TEMPLATE_SKIN_PATH", public_path() . HOME_SKIN_PATH); //带有主题标识的绝对路径
        //主题目录
        define("THEME_PATH",public_path("views/themes/"));
        //前台路径
        define("HOME_ASSET",asset("views/themes/")."/");


        //模块路径
        define("MODULE_PATH",base_path("Modules/"));
        define("MODULE_VIEW","views/modules/");
        define("MODULE_ADMIN_VIEW","views/modules/");

        //前台的module路径
        define("MODULE_ASSET",asset(MODULE_VIEW));
        //后台的module路径
        define("MODULE_ADMIN_ASSET",asset(MODULE_ADMIN_VIEW));

        //检测主题
        define("ADMIN_SKIN","default");

        //admin 路径
        define("ADMIN_SKIN_PATH", "views/admin/");
        define("ADMIN_ASSET",asset("assets/admin").'/');

        //安装路径
        define("INSTALL_ASSET",asset(INSTALL_SKIN_PATH).'/');

        //本地上传路径
        define("UPLOADPATH",asset("/")."uploads/");
        //默认允许的上传文件格式
        define("ALLOWEXT","png,jpeg,jpg,gif,zip,rar,pdf,doc,docx,txt,xls,xlsx,avi,mp3,mp4");
        //忽略权限判断
        define("IGNOREAUTH",array("","logout"));

        //插件路径
        define("PLUGIN_PATH",base_path("Plugins/"));



        return $next($request);
    }
}
