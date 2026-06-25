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
        $keyword = trim((string) ($all['keyword'] ?? ''));
        $type = trim((string) ($all['type'] ?? ''));
        $query = Group::query()
            ->select([
                'group_id',
                'group_name',
                'type',
                'created_at',
                'updated_at',
            ])
            ->selectSub(
                GroupUser::query()
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('group_id', Group::TABLE_NAME . '.' . Group::primaryKey),
                'member_count'
            )
            ->selectRaw('COALESCE(JSON_LENGTH(role_json), 0) as permission_count')
            ->selectRaw("COALESCE(updated_at, created_at) as touched_at")
            ->selectRaw("CASE WHEN type = 'admin' OR group_id IN (1,2) THEN 'system' ELSE 'custom' END as built_in")
            ->selectRaw("CASE WHEN type = 'admin' OR group_id IN (1,2) THEN 'label-danger' ELSE 'label-success' END as built_inCssClass")
            ->selectRaw("CASE WHEN type = 'admin' THEN 'label-primary' ELSE 'label-info' END as typeCssClass")
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('group_name', 'like', '%' . $keyword . '%');
                    if (ctype_digit($keyword)) {
                        $subQuery->orWhere('group_id', (int) $keyword);
                    }
                });
            })
            ->when(in_array($type, ['admin', 'member'], true), function ($q) use ($type) {
                $q->where('type', $type);
            })
            ->orderByRaw("CASE WHEN type = 'admin' OR group_id IN (1,2) THEN 0 ELSE 1 END")
            ->orderBy('group_id');
        $datas = $query->paginate(getLen());
        $stats = $this->getGroupStats();
        $listParams = $this->getListContextParams($all);
        return FormTool::create()
            ->field('group_id', '组ID')
            ->field('group_name', '权限组名称')
            ->field('type', '用户类型', '', 'text', [
                'admin' => '超级管理员',
                'member' => '普通用户',
            ])
            ->field('built_in', '系统标识', '', 'text', [
                'system' => '系统内置',
                'custom' => '自定义',
            ])
            ->field('member_count', '成员数')
            ->field('permission_count', '权限项')
            ->field('touched_at', '最近更新')
            ->searchText('keyword', '搜索组名或组ID', $keyword)
            ->searchSelect('type', '筛选用户类型', $type, self::type())
            ->searchClearEmpty(url('admin/auth/group/list'))
            ->linkAppend($listParams)
            ->tips("总权限组：{$stats['total']}，系统内置：{$stats['system']}，自定义：{$stats['custom']}，已分配成员：{$stats['assigned_users']}，已配置权限的权限组：{$stats['configured_permissions']}。系统内置组不支持直接编辑或删除。")
            ->listAction([
                ['actionName' => '添加', 'actionUrl' => url('admin/auth/group/add'), 'cssClass' => 'bg-info']
            ])
            ->rightAction([
                ['actionName' => '分配权限', 'actionUrl' => url('admin/auth/group/assignPermissions'), 'cssClass' => 'bg-success', 'param' => $listParams],
                ['actionName' => '组成员', 'actionUrl' => url('admin/auth/group/groupUser'), 'cssClass' => 'bg-primary', 'param' => $listParams],
                ['actionName' => '编辑', 'actionUrl' => url('admin/auth/group/edit'), 'cssClass' => 'bg-info', 'notIdArray' => [2], 'param' => $listParams],
                ['actionName' => '删除', 'actionUrl' => url('admin/auth/group/delete'), 'confirm' => true, 'cssClass' => 'bg-danger', 'notIdArray' => [2], 'param' => $listParams]
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
            ->jumpPrevUrl()
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
            ->jumpPrevUrl()
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
            $this->updateCacheGroup($id, ['remove' => true]);
            return redirect($this->buildListUrl($all))->with("pageDataMsg", "删除成功")->with("pageDataStatus", '200');
        } else {
            return back()->with("pageDataMsg", "删除失败")->with("pageDataStatus", "500");
        }
    }

    public function handle() {
        $all = $this->request->all();
        $url = back();
        if (in_array($all['submitType'], ['addGroup', 'editGroup'])) {
            $groupName = trim((string) ($all['group_name'] ?? ''));
            if ($groupName === '') {
                return back()->with("pageDataMsg", "权限组名称不能为空")->with("pageDataStatus", "500");
            }
            if (mb_strlen($groupName) > 50) {
                return back()->with("pageDataMsg", "权限组名称不能超过50个字符")->with("pageDataStatus", "500");
            }
            $add = [
                'group_name' => $groupName,
                'type' => 'member',
                'updated_at' => getDay(),
            ];
            $url = !empty($all['jumpUrl']) ? redirect($all['jumpUrl']) : redirect('admin/auth/group/list');
        }

        switch ($all['submitType']) {
            case 'addGroup':
                if (ServiceModel::groupGetOne(['group_name' => $add['group_name']])) return back()->with("pageDataMsg", "组名已存在")->with("pageDataStatus", "500");
                $add['created_at'] = getDay();
                $res = Group::query()->insertGetId($add);
                $msg = ['添加成功', '添加失败'];
                if ($res) $this->updateCacheGroup($res, ['type' => $add['type'], 'group_name' => $add['group_name'], 'role_array' => []]);
                break;
            case 'editGroup':
                $idKey = Group::primaryKey;
                $id = $all[$idKey];
                if (ServiceModel::groupGetOne(['group_name' => $add['group_name']], $id)) return back()->with("pageDataMsg", "组名已存在")->with("pageDataStatus", "500");
                $customs = Group::query()->find($id);
                if ($customs[$idKey] == 1) return back()->with("pageDataMsg", "该记录不能编辑")->with("pageDataStatus", "500");
                $res = Group::query()->where($idKey, $id)->update($add);
                $msg = ['更新成功', '更新失败'];
                if ($res) $this->updateCacheGroup($id, ['group_name' => $add['group_name']]);
                break;
            case 'assignPermissions':
                $idKey = Group::primaryKey;
                $id = $all[$idKey];
                $roles = $all['role'] ?? [];
                foreach ($roles as &$role) {
                    $role = array_values(array_filter($role));
                }
                $add = [
                    'role_json' => json_encode($roles, JSON_UNESCAPED_UNICODE),
                    'updated_at' => getDay(),
                ];
                $res = Group::query()->where($idKey, $id)->update($add);
                $msg = ['分配成功', '分配失败'];
                if ($res) $this->updateCacheGroup($id, ['role_array' => $roles]);
                $url = !empty($all['jumpUrl']) ? redirect($all['jumpUrl']) : redirect(url('admin/auth/group/assignPermissions?group_id=' . $id));
                break;
            case 'addGroupUser':
                if ($all['group_id'] <= 0) return back()->with("pageDataMsg", '请选择权限组')->with("pageDataStatus", "500");
                if ($all['uid'] <= 0) return back()->with("pageDataMsg", '请选择用户')->with("pageDataStatus", "500");
                if (ServiceModel::apiGetOne(GroupUser::TABLE_NAME, ['uid' => $all['uid']])) return back()->with("pageDataMsg", '该用户已分配组，如需分配，请删除之前分配组')->with("pageDataStatus", "500");
                $res = ServiceModel::add(GroupUser::TABLE_NAME, ['group_id' => $all['group_id'], 'uid' => $all['uid']]);
                $msg = ['分配成功', '分配失败'];
                $url = !empty($all['jumpUrl']) ? redirect($all['jumpUrl']) : redirect(url('admin/auth/group/groupUser?group_id=' . $all['group_id']));
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

    private function getListContextParams(array $all): array
    {
        return array_filter([
            'keyword' => trim((string) ($all['keyword'] ?? '')),
            'type' => trim((string) ($all['type'] ?? '')),
            'page' => (int) ($all['page'] ?? 0),
        ], function ($value) {
            if (is_int($value)) {
                return $value > 0;
            }

            return $value !== '';
        });
    }

    private function buildListUrl(array $all): string
    {
        $params = $this->getListContextParams($all);
        return url('admin/auth/group/list' . ($params ? ('?' . http_build_query($params)) : ''));
    }

    private function getGroupStats(): array
    {
        $baseQuery = Group::query();
        $systemCount = (clone $baseQuery)
            ->where(function ($query) {
                $query->where('type', 'admin')
                    ->orWhereIn(Group::primaryKey, [1, 2]);
            })
            ->count();
        $totalCount = (clone $baseQuery)->count();

        return [
            'total' => $totalCount,
            'system' => $systemCount,
            'custom' => max(0, $totalCount - $systemCount),
            'assigned_users' => GroupUser::query()->count(),
            'configured_permissions' => (clone $baseQuery)
                ->whereRaw('COALESCE(JSON_LENGTH(role_json), 0) > 0')
                ->count(),
        ];
    }

    /********************* 分配权限 ***********************/
    public function assignPermissions() {
        $all = $this->request->all();
        $groupId = (int) ($all[Group::primaryKey] ?? 0);
        $group = Group::query()->find($groupId);
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
            if (strpos($controllerAndAction, '\Admin\\') === false) continue;
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
                $allMenus[$item['identification']]['menus'] = $allMenus2[$item['identification']] ?? [];
                $allMenus[$item['identification']]['name'] = $item['name'];
            }
        }

        $allMenus = $this->normalizePermissionMenuTree($allMenus, $group['role_array']);
        $pageData['group'] = $group;
        $pageData['allMenus'] = $allMenus;
        $pageData['permissionStats'] = $this->buildPermissionStats($allMenus, $group['role_array']);
        $pageData['groupUserUrl'] = $this->buildGroupUserUrl(['group_id' => $groupId]);
        $pageData['groupListUrl'] = $this->buildListUrl([]);
        $pageData['jumpUrl'] = url()->full();
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
        $groupId = (int) ($all['group_id'] ?? 0);
        $group = Group::query()->find($groupId);
        if (!$group) return back()->with("pageDataMsg", "请选择有效权限组")->with("pageDataStatus", "500");
        if ($group['type'] == 'admin') return back()->with("pageDataMsg", "访问错误")->with("pageDataStatus", "500");

        $datas = ServiceModel::groupUser($all);
        $contextParams = $this->getGroupUserContextParams($all);
        return FormTool::create()
            ->field('id', 'ID')
            ->field('uid', '用户ID')
            ->field('username,phone', '用户信息')
            ->field('created_at', '加入时间')
            ->searchText('keyword', '搜索用户名/手机号/UID', trim((string) ($all['keyword'] ?? '')))
            ->searchClearEmpty($this->buildGroupUserUrl(['group_id' => $groupId]))
            ->linkAppend($contextParams)
            ->tips("当前权限组：{$group['group_name']}，成员数：" . GroupUser::query()->where('group_id', $groupId)->count() . "，已分配权限项：" . $this->countRoleItems(json_decode($group['role_json'], true) ?: []) . "。")
            ->listAction([
                ['actionName' => '添加成员', 'actionUrl' => url('admin/auth/group/groupUserAdd'), 'cssClass' => 'bg-info', 'param' => ['group_id' => $groupId]],
                ['actionName' => '分配权限', 'actionUrl' => url('admin/auth/group/assignPermissions'), 'cssClass' => 'bg-success', 'param' => ['group_id' => $groupId]],
                ['actionName' => '返回组列表', 'actionUrl' => url('admin/auth/group/list'), 'cssClass' => 'bg-danger']
            ])
            ->rightAction([
                ['actionName' => '删除', 'actionUrl' => url('admin/auth/group/groupUserDelete'), 'confirm' => true, 'cssClass' => 'bg-danger', 'param' => $contextParams]
            ], 'id')
            ->pageTitle('权限管理')
            ->formTitle('权限组成员')
            ->listView($pageData, $datas);
    }

    //添加
    public function groupUserAdd() {
        $all = $this->request->all();
        $groupId = (int) ($all['group_id'] ?? 0);
        if ($all['is_search'] == 1 && $all['name']) {
            $list = Member::query()
                ->where(function ($query) use ($all) {
                    $query->where('username', 'LIKE', "%{$all['name']}%")
                        ->orWhere('phone', 'LIKE', "%{$all['name']}%");
                    if (ctype_digit((string) $all['name'])) {
                        $query->orWhere('uid', (int) $all['name']);
                    }
                })
                ->whereNotIn('uid', GroupUser::query()->pluck('uid')->toArray())
                ->limit(50)
                ->get(['uid', 'username', 'phone'])
                ->toArray();
            if (!$list) return returnArr(0, '无数据', '');
            $label = "<option value=''>请选择</option>";
            foreach ($list as $l) {
                $label .= "<option value='$l[uid]'>$l[username]【$l[phone]】</option>";
            }
            return returnArr(200, 'ok', $label);
        }
        $group = Group::query()->find($groupId);
        if (!$group) return back()->with("pageDataMsg", "请选择有效权限组")->with("pageDataStatus", "500");
        if ($group['type'] == 'admin') return back()->with("pageDataMsg", "该权限组不支持添加成员")->with("pageDataStatus", "500");
        $pageData = getURIByRoute($this->request);
        $pageData['groupList'] = Group::query()->get()->toArray();
        $pageData['formAction'] = url('admin/auth/group/handle');
        $pageData['group'] = $group;
        $pageData['groupUserUrl'] = $this->buildGroupUserUrl(['group_id' => $groupId]);
        $pageData['availableUserCount'] = Member::query()->whereNotIn('uid', GroupUser::query()->pluck('uid')->toArray())->count();
        $pageData['jumpUrl'] = $pageData['groupUserUrl'];
        return view("auth::admin.group.groupUserAdd", compact('pageData'));
    }

    //删除
    public function groupUserDelete() {
        $all = $this->request->all();
        $idKey = GroupUser::primaryKey;
        $id = $all[$idKey];
        $customs = GroupUser::query()->find($id);
        if (!$customs) return back()->with("pageDataMsg", "记录不存在")->with("pageDataStatus", "500");
        $group = Group::query()->find($customs['group_id']);
        if ($group && $group['type'] == 'admin') return back()->with("pageDataMsg", "该记录不能删除")->with("pageDataStatus", "500");
        if ($customs['uid'] == 1 && $customs['group_id'] == 1) return back()->with("pageDataMsg", "该记录不能删除")->with("pageDataStatus", "500");
        $res = GroupUser::destroy($id);
        if ($res) {
            $redirectAll = array_merge($all, ['group_id' => $customs['group_id']]);
            return redirect($this->buildGroupUserUrl($redirectAll))->with("pageDataMsg", "删除成功")->with("pageDataStatus", '200');
        } else {
            return back()->with("pageDataMsg", "删除失败")->with("pageDataStatus", "500");
        }
    }

    private function getGroupUserContextParams(array $all): array
    {
        return array_filter([
            'group_id' => (int) ($all['group_id'] ?? 0),
            'keyword' => trim((string) ($all['keyword'] ?? '')),
            'page' => (int) ($all['page'] ?? 0),
        ], function ($value) {
            if (is_int($value)) {
                return $value > 0;
            }

            return $value !== '';
        });
    }

    private function buildGroupUserUrl(array $all): string
    {
        $params = $this->getGroupUserContextParams($all);
        return url('admin/auth/group/groupUser' . ($params ? ('?' . http_build_query($params)) : ''));
    }

    private function countRoleItems(array $roles): int
    {
        $count = 0;
        foreach ($roles as $roleGroup) {
            $count += count(array_filter((array) $roleGroup));
        }

        return $count;
    }

    private function normalizePermissionMenuTree(array $allMenus, array $roleArray): array
    {
        ksort($allMenus);
        foreach ($allMenus as $moduleKey => &$module) {
            $module['menus'] = $module['menus'] ?? [];
            $module['selected_count'] = 0;
            $module['total_count'] = 0;
            foreach ($module['menus'] as &$menuGroup) {
                $values = [];
                $parentValue = $menuGroup['url'] ?: $menuGroup['title'];
                if ($parentValue) {
                    $values[] = $parentValue;
                }
                foreach (($menuGroup['submenu'] ?? []) as $submenu) {
                    $values[] = $submenu['url'];
                }
                $values = array_values(array_unique(array_filter($values)));
                $selectedValues = array_values(array_intersect($values, $roleArray[$moduleKey] ?? []));
                $menuGroup['permission_values'] = $values;
                $menuGroup['selected_values'] = $selectedValues;
                $menuGroup['selected_count'] = count($selectedValues);
                $menuGroup['total_count'] = count($values);
                $module['selected_count'] += $menuGroup['selected_count'];
                $module['total_count'] += $menuGroup['total_count'];
            }
        }

        return $allMenus;
    }

    private function buildPermissionStats(array $allMenus, array $roleArray): array
    {
        $moduleCount = count($allMenus);
        $menuGroupCount = 0;
        $actionCount = 0;
        foreach ($allMenus as $module) {
            $menuGroupCount += count($module['menus'] ?? []);
            $actionCount += (int) ($module['total_count'] ?? 0);
        }

        return [
            'modules' => $moduleCount,
            'groups' => $menuGroupCount,
            'actions' => $actionCount,
            'selected' => $this->countRoleItems($roleArray),
        ];
    }
}
