<?php
/*
 * @$event 事件名称
 * @array $data 事件数据
 * @callback 回调函数
 * */
function hook($event, array $data=[], $callback=null) {
    if(!is_array($data)) throw new Exception("hook parameters data must be array");
    //模块名称
    if ($data['moduleName']) {
        //目录类型
        if ($data['cloudType'] == 'plugin') {
            $classname = "Plugins\\" . ucfirst($data['moduleName']) . "\\Events\\" . $event;
        } else {
            $classname = "Modules\\" . ucfirst($data['moduleName']) . "\\Events\\" . $event;
        }
        //模块或者插件存在
        if (class_exists($classname)) {
            $return1 = event(new $classname($data));
        }
        $data['classname'] = $classname;
    }else{
        //指定目录文件存在
        if (class_exists($event)) {
            $return1 =  event(new $event($data));
            $data['classname'] = $event;
        }
    }
    //默认事件目录文件存在
    $classname = "App\\Events\\" . $event;
    if (class_exists($classname)) {
        $return2 =  event(new $classname($data));
        $data['classname'] = $classname;
    }
    if($callback && is_callable($callback)){
        $return3 = $callback();
    }
    return array_merge($return1 ?? [], $return2 ?? [], $return3 ?? []);
}
