<?php

namespace Modules\Main\Http\Controllers\Admin;

use Illuminate\Support\Facades\Cache;
use Modules\Main\Models\HomeMenu;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Mxzcms\Modules\cache\CacheKey;

class MenuController extends ModulesController {

    //菜单列表
    public function themeMenuList() {

        if ($this->request->ajax()) {
            $data = ServiceModel::getThemeMenuList();
            return ['code' => 0, 'msg' => 'ok', 'data' => $data, 'count' => count($data)];
        }
        return view('admin/func/themeMenuList', [

        ]);
    }

    //添加菜单
    public function themeMenuAdd() {
        $all = $this->request->all();
        if ($this->request->ajax()) {
            if (!trim($all['name'])) return returnArr(0, '名称不能为空');



            $add = [
                'module' => $all['module'] ?: 'Main',
                'position' => $all['position'],
                'pid' => intval($all['pid']),
                'sort' => intval($all['sort']),
                'name' => trim($all['name']),
                'url' => $all['url'] ?: '#',
                'icon' => $all['icon'] ?: '',
                'icon_character' => $all['icon_character'] ?: '',
                'created_at' => getDay(),
                'updated_at' => getDay(),
            ];

            if ($_FILES['cover']['size'] > 0) {
                //文件上传
                try {
                    $add['cover'] = UploadFile(\Request(), "cover", "cover/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                    $this->resizeImg($all['avatar'], 50, 100, 100);
                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }
            }

            $res = HomeMenu::query()->insertGetId($add);
            if ($res) {
                return returnArr(200, '添加成功');
            } else {
                return returnArr(0, '添加失败');
            }
        }
        $menuList = ServiceModel::getHomeMenu();
        $moduleMenu = hook('GetModuleHomeSetMenu', ['moduleName' => 'System']);
        $modelMenu = hook('GetModelMenu', ['moduleName' => 'Formtools'])[0];
        $moduleArray = array_column(Cache::get(CacheKey::ModulesActive), 'name', 'identification');

        return view('admin/func/themeMenuAdd', [
            'menuList' => $menuList,
            'moduleArray' => $moduleArray,
            'moduleMenu' => $moduleMenu,
            'modelMenu' => $modelMenu,
        ]);
    }

    //编辑菜单
    public function themeMenuEdit() {
        $all = $this->request->all();
        $data = HomeMenu::query()->find($all['id']);
        if ($this->request->ajax()) {
            if (!$data) return returnArr(0, '数据不存在');
            if (!trim($all['name'])) return returnArr(0, '名称不能为空');
            $add = [
                'module' => $all['module'] ?: 'Main',
                'position' => $all['position'],
                'pid' => intval($all['pid']),
                'sort' => intval($all['sort']),
                'name' => trim($all['name']),
                'url' => $all['url'] ?: '#',
                'icon' => $all['icon'] ?: '',
                'icon_character' => $all['icon_character'] ?: '',
                'status' => $all['status'] == 1 ? 1 : 2,
                'updated_at' => getDay(),
            ];

            if ($_FILES['cover']['size'] > 0) {
                //文件上传
                try {
                    $add['cover'] = UploadFile(\Request(), "cover", "cover/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                    $this->resizeImg($all['avatar'], 50, 100, 100);
                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }
            }

            $res = HomeMenu::query()->where('id', $all['id'])->update($add);
            if ($res) {
                return returnArr(200, '编辑成功');
            } else {
                return returnArr(0, '编辑失败');
            }
        }
        if (!$data) return back()->with("errormsg", "数据不存在");
        $menuList = ServiceModel::getHomeMenu();
        $moduleMenu = hook('GetModuleHomeSetMenu', ['moduleName' => 'System']);
        $modelMenu = hook('GetModelMenu', ['moduleName' => 'Formtools'])[0];
        $moduleArray = array_column(Cache::get(CacheKey::ModulesActive), 'name', 'identification');
        return view('admin/func/themeMenuEdit', [
            'data' => $data,
            'menuList' => $menuList,
            'moduleArray' => $moduleArray,
            'moduleMenu' => $moduleMenu,
            'modelMenu' => $modelMenu,
        ]);
    }

    //删除菜单
    public function themeMenuDelete() {
        $all = $this->request->all();
        $data = HomeMenu::find($all['id']);
        if (!$data) return back()->with("errormsg", "数据不存在");

        if (HomeMenu::query()->where('pid', $all['id'])->first()) {
            return back()->with("errormsg", "存在下级，不能删除");
        }

        if (HomeMenu::destroy($all['id'])) {
            return back()->with("successmsg", "删除成功");
        }
        return back()->with("errormsg", "删除失败");
    }

    //菜单启用禁用
    public function themeMenuChangeStatus() {
        $all = $this->request->all();
        $data = HomeMenu::find($all['id']);
        if (!$data) return back()->with("errormsg", "数据不存在");
        if (ServiceModel::whereUpdate(HomeMenu::TABLE_NAME, ['id' => $all['id']], ['status' => $all['status'] == 1 ? 1 : 2, 'updated_at' => getDay()])) {
            return back()->with("successmsg", "操作成功");
        }
        return back()->with("errormsg", "操作失败");
    }

    public function themeMenuSearchModuleMenu() {
        $all = $this->request->all();
        if (!$all['module']) return returnArr(0, '模块不能为空');
        if (!$all['table']) return returnArr(0, '表名不能为空');
        if (!$all['title']) return returnArr(0, '标题不能为空');
        try {
            $res = hook('SearchMenuFromModule', [
                'moduleName' => $all['module'],
                'table' => $all['table'],
                'title' => $all['title'],
            ])[0];
        } catch (\Exception $exception) {
            dd($exception);
        }
        return returnArr(200, 'ok', $res);
    }
}

