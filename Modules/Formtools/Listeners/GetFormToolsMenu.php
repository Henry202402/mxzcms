<?php

namespace Modules\Formtools\Listeners;

use Modules\Formtools\Models\FormModel;

class GetFormToolsMenu {

    public function handle(\Modules\Formtools\Events\GetFormToolsMenu $event) {
        $data = $event->data;
        $moduleName = ucfirst($data['moduleName']);
        $lowerModuleName = strtolower($moduleName);
        $moduleConfig = config('modules.' . $lowerModuleName);
        /*$menu = [];
        if ($moduleConfig['addmodel'] == 1 || $lowerModuleName == 'formtools') {
            $allmodels = FormModel::query()
                ->where(function ($q) use ($moduleConfig, $moduleName) {
                    if ($moduleConfig['addmodel'] == 1) $q->where('module', $moduleName);
                })
                ->select(['id', 'name', 'menuname', 'identification', 'module', 'supermodel', 'icon'])
                ->get();
        }
        foreach ($allmodels as $key => $value) {
            if (!$value->supermodel) {
                $menuKey = $value->id;
            } else {
                $menuKey = $value->supermodel;
            }
            $menu[$menuKey]['icon'] = $value->icon;
            $menu[$menuKey]['title'] = $value->menuname;
            $menu[$menuKey]['model'] = $value->identification;
            $menu[$menuKey]['controller'] = "Model";
            $menu[$menuKey]['action'] = "#";
            $menu[$menuKey]['url'] = '#';
            $menu[$menuKey]['submenu'][] = [
                'icon' => $value->icon,
                'title' => $value->name,
                "controller" => "Model",
                "action" => "model?action=List&model=" . $value->identification,
                'url' => "admin/formtools/model?moduleName={$moduleName}&action=List&model=" . $value->identification,
            ];
        }*/

        if ($moduleConfig['addmodel'] == "y" || $lowerModuleName == 'formtools') {
            $topModel = FormModel::query()
                ->where(function ($q) use ($moduleConfig, $moduleName,$lowerModuleName) {
                    if ($moduleConfig['addmodel'] == "y" && $lowerModuleName != 'formtools') $q->where('module', $moduleName);
                })
                ->groupBy('menuname')
                ->select(['id', 'name', 'menuname', 'identification', 'icon', 'module'])
                ->get()->toArray();
            foreach ($topModel as $key => &$value) {
                $value['title'] = $value['menuname'];
                $value['model'] = $value['identification'];
                $value['controller'] = "Model";
                $value['action'] = "#";
                $value['url'] = '#';
                unset($value['id'], $value['name'], $value['menuname'], $value['identification']);
                $submenu = FormModel::query()
                    ->where('menuname', $value['title'])
                    ->where('module', $value['module'])
                    ->select(['id', 'name', 'menuname', 'identification', 'icon'])
                    ->get()->toArray();
                foreach ($submenu as &$val) {
                    $val['title'] = $val['name'];
                    $val['controller'] = 'Model';
                    $val['action'] = "model?action=List&model={$val['identification']}";
                    $val['url'] = "admin/formtools/model?moduleName={$moduleName}&action=List&model={$val['identification']}";
                }
                $value['submenu'] = $submenu ?: [];
            }
        }
        return $topModel;
    }
}
