<?php

//获取条数
function getLen($all = []) {
    $len = intval($all['pagesize']) ?: $_REQUEST['pagesize'];
    return intval($len) > 0 ? intval($len) : __E('admin_page_count');
}

//获取时间格式
function getDay($type = 1) {
    if ($type == 2) {
        return date('Y-m-d');
    } else {
        return date('Y-m-d H:i:s');
    }
}

//处理时间范围
function dealTimeRang($str, $type = 1) {
    $arr = explode(' - ', $str);
    if ($type == 1) {
        $arr = [$arr[0] . ' 00:00:00', $arr[1] . ' 23:59:59'];
    }
    return $arr;
}

//返回数组json
function returnJson($status, $msg, $data = array(), $other = array()) {
    return json_encode(['status' => $status, 'msg' => $msg, 'data' => $data, 'other' => $other], JSON_UNESCAPED_UNICODE);
}

//返回数组
function returnArr($status, $msg, $data = array(), $other = array()) {
    return ['status' => $status, 'msg' => $msg, 'data' => $data, 'other' => $other];
}

//返回数组json
function dealPage($data = array()) {
    $tmp['list'] = $data['data'];
    $tmp['page'] = $data['current_page'];
    $tmp['pagesize'] = $data['per_page'];
    $tmp['total'] = $data['total'];
    return $tmp;
}

function backMsg($msg) {
    return back()->with('errormsg', $msg);
}


function moduleHomeTemplate($moduleName) {
    $dir = 'home';
    $str = strtolower($moduleName) . "::{$dir}.";
    return $str;
}

//模块后台的静态资源文件路径
function moduleHomeResource($moduleName = '', $assets = 'assets') {
    $moduleName = strtolower($moduleName);
    $str = asset("views/modules/$moduleName/".$assets);
    return $str;
}


//后台模块的模板地址简化函数
//参数$moduleName 是模块名
function moduleAdminTemplate($moduleName) {
    $str = strtolower($moduleName) . "::admin.";
    return $str;
}

//模块后台的静态资源文件路径
function moduleAdminResource($moduleName = '') {
    $str = MODULE_ADMIN_ASSET . "/assets";
    return $str;
}


//模块后台跳转链接简化
function moduleAdminJump($moduleName, $path) {
    $str = url('admin/' . strtolower($moduleName)) . '/' . $path;
    return $str;
}

//模块后台跳转链接简化
function moduleHomeJump($moduleName, $path) {
    $str = url($moduleName) . '/' . $path;
    return $str;
}

/**
 * 返回信息
 * @param $arr [200,'成功','/admin/index']
 * @return redirect
 */

function oneFlash($arr) {
    session(['pageDataStatus' => $arr[0]]);
    session(['pageDataMsg' => $arr[1]]);
    if ($arr[2]) return redirect($arr[2]);
    return back();
}

//获取URI详情
function getURIByRoute($request) {
    if ($request->route()) {
        $return['uri'] = $request->route()->uri;
        $return['namespace'] = $request->route()->getAction()['namespace'];
        $return['moduleName'] = explode('\\', $request->route()->getAction()['namespace'])[1];
        $temp = explode('@', str_replace($return['namespace'] . '\\', '', $request->route()->getAction()['controller']));
        $return['controller'] = str_replace('Controller', '', $temp[0]);
        $return['action'] = $temp[1];
        return $return;
    }
    return [];
}

function HomeView($viewPath, $data = []) {
    //获取当前主题
    if (cache()->has('theme')) {
        $theme = cache()->get('theme');
    } else {
        $theme = \Illuminate\Support\Facades\DB::table('themes')->where('status', '1')->value('identification');
        if (!$theme) abort(500, "请先安装和启用主题模板");
        cache()->forever('theme', $theme);
    }
    return view("themes." . $theme . "." . $viewPath, $data);
}

function ModelView($viewPath, $data = []) {
    //获取当前主题
    if (cache()->has('theme')) {
        $theme = cache()->get('theme');
    } else {
        $theme = \Illuminate\Support\Facades\DB::table('themes')->where('status', '1')->value('identification');
        cache()->forever('theme', $theme);
    }
    if (file_exists(public_path('views/themes/' . $theme . '/model/overwrite/' . $viewPath . '.blade.php'))) {
        $data['template'] = "themes.{$theme}.model.overwrite.{$viewPath}";
    } else {
        $data['template'] = "model.{$viewPath}";
    }
    return view("themes.{$theme}.model.LoadTemplate", $data);
}

function getListByModel($model, $limit = 1, $orderby = "desc") {

    if (!is_array($model)) {
        $model = (array)$model->toarray();
    }

    if ($model["data_source"] == "api") {
        $data_source_field_mapping = explode('\n', $model['data_source_field_mapping']);
        $data_source_field_mappings = [];
        foreach ($data_source_field_mapping as $v) {
            $temp = explode("=>", $v);
            if (count($temp) == 2) $data_source_field_mappings[$temp[0]] = $temp[1];
        }

        $curldatas = json_decode(curl_request($model["data_source_api_url"]), true);
        $data = [];
        foreach ($curldatas['data'] as $key => $value) {
            if ($key >= $limit) {
                break;
            }
            foreach ($data_source_field_mappings as $k => $val) {
                $value[$k] = $value[$val];
            }
            $data[] = (object)$value;
        }

        return $data;
    }

    $data = \Illuminate\Support\Facades\DB::table("module_formtools_" . $model['identification']);

    $data = $data->orderBy("id", $orderby);
    if ($limit) {
        $data = $data->limit($limit);
    }
    $data = $data->get();

    return $data;
}

function getFormRadioList($array) {
    $list = [];
    foreach ($array as $key => $item) {
        $list[] = ['value' => $key, 'name' => $item];
    }
    return $list;
}

