<?php

namespace Modules\Main\Http\Controllers\Admin;

use Modules\Formtools\Http\Controllers\Admin\FormTool;
use Modules\ModulesController;
use function back;
use function base_path;
use function getURIByRoute;
use function url;

class PluginController extends ModulesController {

    public function config() {
        //换成钩子代替
        $all = $this->request->all();
        if(!$all['identification']){
            return redirect(url("admin/plugin"));
        }
        $identification = $all['identification'];
        $path = base_path("Plugins/{$identification}/Config/config.php");
        $configArr = include $path;
        $config = $configArr['config'] ?: [];
        if ($this->request->isMethod('POST')) {

            unset($all['_token'], $all['identification'],$all['requestid']);
            foreach ($all as $key => $value) {
                $config[$key]['value'] = $value;
            }

            $configArr['config'] = $config;

            $new_data = var_export($configArr, true);
            $fp = fopen($path, "w+");
            if (fwrite($fp, "<?php\r\n\r\n return " . $new_data . ";")) {
                fclose($fp);
                return back()->with('successmsg','编辑成功');
            } else {
                return back()->with('errormsg','编辑失败');
            }
        }
        $pageData = getURIByRoute($this->request);
        $create = FormTool::create()
            ->csrf_field()
            ->field('identification', ['value' => $identification, 'formtype' => 'hidden'])
            ->formTitle($configArr['name']." - 插件配置")
            ->pageTitle("插件管理")
            ->formaction(url($pageData['uri']));

        foreach ($config as $key => $c) {
            $create->field($key, [
                'name' => $c['name'],
                'value' => $c['value'],
                'formtype' => $c['formtype'],
                'datas' => $c['datas'] ?? [],
                'notes' => $c['notes']
            ]);
        }

        $pageData = $create->getData();

        return view('admin/func/plugin-config', compact('pageData'));
//        return $create->view();
    }

}

