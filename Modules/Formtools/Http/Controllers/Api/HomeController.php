<?php

namespace Modules\Formtools\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Modules\Formtools\Models\FormModel;
use Modules\ModulesController;

class HomeController extends ModulesController {
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        echo "Modules\Formtools\Http\Controllers\Api";
    }

    public function release($access_identification)
    {
        $all = $this->request->all();
        if($all['api_key'] != cacheGlobalSettingsByKey('api_key',"Formtools")){
            return returnArr(0,'密钥不允许',$all,'');
        }

        if($access_identification){
            $data = FormModel::query()->where('access_identification',$access_identification)->first();
            $fields = json_decode($data->fields,true);
            $insert = [];
            foreach($fields as $field){
                $insert[$field['identification']] = $all[$field['identification']];
            }

            $insert['seo_title'] = $all['seo_title'];
            $insert['seo_keywords'] = $all['seo_keywords'];
            $insert['seo_description'] = $all['seo_description'];
            $insert['status'] = 1;
            $insert['created_at'] = date('Y-m-d H:i:s');
            $res = DB::table("module_formtools_".$access_identification)->insert($insert);
            if($res){
                return returnArr(200,'插入成功!',$all,'');
            }
            return returnArr(0,'插入失败',$all,'');
        }

        return returnArr(0,'访问标识不允许',$all,'');
    }

}
