<?php

namespace Modules\Auth\Http\Controllers\Admin;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\Common\CacheKey;
use Modules\Auth\Models\Group;
use Modules\Auth\Models\GroupUser;
use Modules\Auth\Services\ServiceModel;
use Modules\Formtools\Http\Controllers\Admin\FormTool;
use Modules\Main\Models\Member;
use Modules\ModulesController;
use Modules\System\Http\Controllers\Common\SessionKey;

class GroupController extends ModulesController {
    public static function type() {
        return [
            ['value' => 'admin', 'name' => '超级管理员'],
            ['value' => 'member', 'name' => '用户'],
        ];
    }

    /********************* 权限组 ***********************/
    //列表
    public function list() {
        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);
        $datas = Group::query()
            ->paginate(getLen());
        return FormTool::create()
            ->field('group_id', '组ID')
            ->field('group_name', '权限组名称')
            ->field('created_at', '时间')
            ->listAction([
                ['actionName' => '添加', 'actionUrl' => url('admin/auth/group/add'), 'cssClass' => 'bg-info']
            ])
            ->rightAction([
                ['actionName' => '分配权限', 'actionUrl' => url('admin/auth/group/assignPermissions'), 'cssClass' => 'bg-success'],
                ['actionName' => '组成员', 'actionUrl' => url('admin/auth/group/groupUser'), 'cssClass' => 'bg-primary'],
                ['actionName' => '编辑', 'actionUrl' => url('admin/auth/group/edit'), 'cssClass' => 'bg-info', 'notIdArray' => [2]],
                ['actionName' => '删除', 'actionUrl' => url('admin/auth/group/delete'), 'confirm' => true, 'cssClass' => 'bg-danger', 'notIdArray' => [2]]
            ], 'group_id', [1])
            ->pageTitle('权限控制')
            ->formTitle('权限组列表')
            ->listView($pageData, $datas);
    }

    //添加
    public function add() {
        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);
        return FormTool::create()
            ->field('submitType', ['formtype' => 'hidden', 'value' => 'addGroup', 'required' => true])
            ->field('group_name', ['name' => '权限组名称', 'required' => true])
            ->csrf_field()
            ->pageTitle('权限管理')
            ->formTitle('添加权限组')
            ->formAction(url('admin/auth/group/handle'))
            ->formView($pageData);
    }

    //编辑
    public function edit() {
        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);
        $idKey = Group::primaryKey;
        $id = $all[$idKey];
        $data = Group::query()->find($id);
        if (!$data) return back()->with("pageDataMsg", "记录不存在")->with("pageDataStatus", "500");
        if ($data['type'] == 'admin') return back()->with("pageDataMsg", "该记录不能编辑")->with("pageDataStatus", "500");
        if (in_array($data[$idKey], [1, 2])) return back()->with("pageDataMsg", "该记录不能编辑")->with("pageDataStatus", "500");
        return FormTool::create()
            ->field('submitType', ['formtype' => 'hidden', 'value' => 'editGroup', 'required' => true])
            ->field($idKey, ['formtype' => 'hidden', 'value' => $data[$idKey], 'required' => true])
            ->field('group_name', ['name' => '权限组名称', 'value' => $data['group_name'], 'required' => true])
            ->csrf_field()
            ->pageTitle('权限管理')
            ->formTitle('编辑权限组')
            ->formAction(url('admin/auth/group/handle'))
            ->formView($pageData);
    }

    //删除
    public function delete() {
        $all = $this->request->all();
        $idKey = Group::primaryKey;
        $id = $all[$idKey];
        $customs = Group::query()->find($id);
        if (!$customs) return back()->with("pageDataMsg", "记录不存在")->with("pageDataStatus", "500");
        if ($customs['type'] == 'admin') return back()->with("pageDataMsg", "该记录不能删除")->with("pageDataStatus", "500");
        if (in_array($customs[$idKey], [1, 2])) return back()->with("pageDataMsg", "该记录不能删除")->with("pageDataStatus", "500");
        $res = Group::destroy($id);
        if ($res) {
            return redirect('admin/auth/group/list')->with("pageDataMsg", "删除成功")->with("pageDataStatus", '200');
        } else {
            return back()->with("pageDataMsg", "删除失败")->with("pageDataStatus", "500");
        }
    }

    public function handle() {
        $all = $this->request->all();
        $url = back();
        if (in_array($all['submitType'], ['addGroup', 'editGroup'])) {
            $add = [
                'group_name' => trim($all['group_name']),
                'type' => 'member',
                'updated_at' => getDay(),
            ];
            $url = redirect('admin/auth/group/list');
        }

        switch ($all['submitType']) {
            case 'addGroup':
                if (ServiceModel::groupGetOne(['group_name' => $add['group_name']])) return back()->with("pageDataMsg", "组名已存在")->with("pageDataStatus", "500");
                $add['created_at'] = getDay();
                $res = Group::query()->insertGetId($add);
                $msg = ['添加成功', '添加失败'];
                if ($res) $this->updateCacheGroup($res, ['type' => $add['type'], 'group_name' => $add['group_name']]);
                break;
            case 'editGroup':
                $idKey = Group::primaryKey;
                $id = $all[$idKey];
                if (ServiceModel::groupGetOne(['group_name' => $add['group_name']], $id)) return back()->with("pageDataMsg", "组名已存在")->with("pageDataStatus", "500");
                $customs = Group::query()->find($id);
                if ($customs[$idKey] == 1) return back()->with("pageDataMsg", "该记录不能编辑")->with("pageDataStatus", "500");
                $res = Group::query()->where($idKey, $id)->update($add);
                $msg = ['更新成功', '更新失败'];
                if ($res) $this->updateCacheGroup($res, ['group_name' => $add['group_name']]);
                break;
            case 'assignPermissions':
                $idKey = Group::primaryKey;
                $id = $all[$idKey];
                foreach ($all['role'] as &$role) {
                    $role = array_values(array_filter($role));
                }
                $add = [
                    'role_json' => json_encode($all['role'], JSON_UNESCAPED_UNICODE),
                    'updated_at' => getDay(),
                ];
                $res = Group::query()->where($idKey, $id)->update($add);
                $msg = ['分配成功', '分配失败'];
                if ($res) $this->updateCacheGroup($id, ['role_array' => $all['role']]);
                break;
            case 'addGroupUser':
                if ($all['group_id'] <= 0) return back()->with("pageDataMsg", '请选择权限组')->with("pageDataStatus", "500");
                if ($all['uid'] <= 0) return back()->with("pageDataMsg", '请选择用户')->with("pageDataStatus", "500");
                if (ServiceModel::apiGetOne(GroupUser::TABLE_NAME, ['uid' => $all['uid']])) return back()->with("pageDataMsg", '该用户已分配组，如需分配，请删除之前分配组')->with("pageDataStatus", "500");
                $res = ServiceModel::add(GroupUser::TABLE_NAME, ['group_id' => $all['group_id'], 'uid' => $all['uid']]);
                $msg = ['分配成功', '分配失败'];
                break;
            default:
                return back()->with("pageDataMsg", "类型错误")->with("pageDataStatus", "500");
        }

        if ($res) {
            return $url->with("pageDataMsg", $msg[0])->with("pageDataStatus", '200');
        } else {
            return back()->with("pageDataMsg", $msg[1])->with("pageDataStatus", "500");
        }
    }

    public function getGroupList($type = 1) {
        return hook('GetPermissionGroupInfoList', ['type' => $type])[0]['list'];
    }

    public function updateCacheGroup($id, $array) {
        hook('SetPermissionGroupInfoList', ['id' => $id, 'array' => $array])[0];
    }

    /********************* 分配权限 ***********************/
    public function assignPermissions() {
        $all = $this->request->all();
        $group = Group::query()->find($all[Group::primaryKey]);
        if (!$group) return back()->with("pageDataMsg", '记录不存在')->with("pageDataStatus", "500");
        if ($group['type'] == 'admin') return back()->with("pageDataMsg", "该记录不需要分配")->with("pageDataStatus", "500");
        $group['role_array'] = json_decode($group['role_json'], true) ?: [];

        $pageData = getURIByRoute($this->request);
        $pageData['title'] = "权限分配";
        $pageData['subtitle'] = "权限组分配权限";
        $allMenus = $allMenus2 = [];
        //获取所有菜单
        /*foreach (cache(\Mxzcms\Modules\cache\CacheKey::ModulesActive) as $item) {
            $config = include module_path($item['identification'], "Config/config.php");
            $item = array_merge($config, $item);

            if ($item['status'] == 1 && $item['auth'] == 1 && is_file(module_path($item['identification'], "Config/menus.php"))) {
                $allMenus[$item['identification']]['menus'] = include module_path($item['identification'], "Config/menus.php");
                $allMenus[$item['identification']]['name'] = $item['name'];
            }
        }*/

        $tmpList = [];
        $pageData['allMenus'] = $allMenus;
        $routers = Route::getRoutes();
        foreach ($routers as $router) {
            $controllerAndAction = $router->action['controller'];
            $module = explode("\\", $controllerAndAction)[1];
            $indexArr = explode('@', $router->action['as']);
            if ($indexArr[0]) {
                $tmpList[$module][$indexArr[0]][] = [
                    'title' => $indexArr[1] ?: $router->uri,
                    'url' => $router->uri,
                ];
            }
        }
        foreach ($tmpList as $tk => $tlv) {
            foreach ($tlv as $ttk => $ttlv) {
                $allMenus2[$tk][] = [
                    'title' => $ttk,
                    'submenu' => $ttlv,
                ];
            }
        }
        foreach (cache(\Mxzcms\Modules\cache\CacheKey::ModulesActive) as $item) {
            $config = include module_path($item['identification'], "Config/config.php");
            $item = array_merge($config ?: [], $item ?: []);
            if ($item['status'] == 1 && $item['auth'] == "y" && is_file(module_path($item['identification'], "Config/menus.php"))) {
                $allMenus[$item['identification']]['menus'] = $allMenus2[$item['identification']];
                $allMenus[$item['identification']]['name'] = $item['name'];
            }
        }

        $pageData['group'] = $group;
        $pageData['allMenus'] = $allMenus;
//        dump($allMenus);
        /*foreach ($routers as $router) {
            if ($router->action) {
                $temp = explode("\\", $router->action['controller']);
                $module = $temp[1];
                $controller = str_replace("Controller", "", explode('@', $temp[5])[0]);
                if ($allMenus[$module] && strstr($router->uri, 'admin/')) {
                    //追加子菜单
                    foreach ($allMenus[$module] as $key => $menuTemps) {
                        foreach ($menuTemps as $key2 => $menuTemp) {
                            if ($menuTemp['controller'] == $controller) {
                                $add = false;
                                foreach ($pageData['allMenus'][$module][$key][$key2]['submenu'] as $check) {
                                    if (strstr($check['url'], $router->uri)) {
                                        $add = true;
                                    }
                                }
                                if ($add) continue;
                                $pageData['allMenus'][$module][$key][$key2]['submenu'][] = [
                                    'title' => $router->action['as'] ?: $router->uri,
                                    'icon' => '',
                                    'url' => $router->uri,
                                    'order' => 100,
                                    "controller" => $controller,
                                    "action" => $router->uri,
                                ];
                            }
                        }
                    }
                }

            }
        }*/

//        dd($pageData['allMenus']);
        return view("auth::admin.group.assignPermissions", compact('pageData'));

    }

    /********************* 组成员 ***********************/
    //列表
    public function groupUser() {
        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);

        $datas = ServiceModel::groupUser($all);
        if ($datas[0]['type'] == 'admin') return back()->with("pageDataMsg", "访问错误")->with("pageDataStatus", "500");
        return FormTool::create()
            ->field('id', 'ID')
            ->field('group_name', '权限组名称')
            ->field('username,phone', '用户信息')
            ->field('created_at', '时间')
            ->listAction([
                ['actionName' => '添加', 'actionUrl' => url('admin/auth/group/groupUserAdd'), 'cssClass' => 'bg-info', 'param' => ['group_id' => $all['group_id']]],
                ['actionName' => '返回', 'actionUrl' => url('admin/auth/group/list'), 'cssClass' => 'bg-danger']
            ])
            ->rightAction([
                ['actionName' => '删除', 'actionUrl' => url('admin/auth/group/groupUserDelete'), 'confirm' => true, 'cssClass' => 'bg-danger']
            ], 'id')
            ->pageTitle('权限管理')
            ->formTitle('权限组成员')
            ->listView($pageData, $datas);
    }

    //添加
    public function groupUserAdd() {
        $all = $this->request->all();
        if ($all['is_search'] == 1 && $all['name']) {
            $list = Member::query()
                ->where('username', 'LIKE', "%{$all['name']}%")
                ->orWhere('phone', 'LIKE', "%{$all['name']}%")
                ->get(['uid', 'username', 'phone'])
                ->toArray();
            if (!$list) return returnArr(0, '无数据', '');
            $label = "<option value=''>请选择</option>";
            foreach ($list as $l) {
                $label .= "<option value='$l[uid]'>$l[username]【$l[phone]】</option>";
            }
            return returnArr(200, 'ok', $label);
        }
        $pageData = getURIByRoute($this->request);
        $pageData['groupList'] = Group::query()->get()->toArray();
        $pageData['formAction'] = url('admin/auth/group/handle');
        return view("auth::admin.group.groupUserAdd", compact('pageData'));
    }

    //删除
    public function groupUserDelete() {
        $all = $this->request->all();
        $idKey = GroupUser::primaryKey;
        $id = $all[$idKey];
        $customs = GroupUser::query()->find($id);
        if (!$customs) return back()->with("pageDataMsg", "记录不存在")->with("pageDataStatus", "500");
        if ($customs['type'] == 'admin') return back()->with("pageDataMsg", "该记录不能删除")->with("pageDataStatus", "500");
        if ($customs['uid'] == 1 && $customs['group_id'] == 1) return back()->with("pageDataMsg", "该记录不能删除")->with("pageDataStatus", "500");
        $res = GroupUser::destroy($id);
        if ($res) {
            return back()->with("pageDataMsg", "删除成功")->with("pageDataStatus", '200');
        } else {
            return back()->with("pageDataMsg", "删除失败")->with("pageDataStatus", "500");
        }
    }
}
