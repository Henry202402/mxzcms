<?php

namespace Modules\System\Listeners;

class AdminSidebarMenu {

    public function handle(\App\Events\AdminSidebarMenu $event) {
        //事件逻辑 ...
        $pageData = $event->data['pageData'];
        $moduleName = ucfirst($pageData['moduleName']);
        //本模块菜单
        $menus = include module_path(ucfirst($moduleName), "Config/menus.php");

        //模型菜单
        $data = event(new \Modules\Formtools\Events\GetFormToolsMenu(['moduleName' => $moduleName]))[0];
        $menus = array_merge($menus ?: [], $data ?: []);

        $config = include module_path($moduleName, 'Config/config.php');
        if ($config['auth'] == 'y') {
            $roleArray = session(\Modules\System\Http\Controllers\Common\SessionKey::CurrentUserPermissionGroupInfo);
            $roleModule = $roleArray['role_array'][$moduleName];
            foreach ($menus as $key => $menu) {
                if ($roleArray['type'] != 'admin' && (!in_array($menu['title'], $roleModule) && !in_array($menu['url'], $roleModule))) {
                    unset($menus[$key]);
                }
                foreach ($menu['submenu'] as $k => $m) {
                    if ($roleArray['type'] != 'admin' && (!in_array($m['title'], $roleModule) && !in_array($m['url'], $roleModule))) {
                        unset($menus[$key]['submenu'][$k]);
                    }
                }
            }
        }

        return view('system::admin.public.sidebarmenu', compact('menus', "pageData"));
    }

}
