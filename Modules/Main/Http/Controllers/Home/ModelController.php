<?php

namespace Modules\Main\Http\Controllers\Home;

use Illuminate\Http\Request;
use Modules\Formtools\Models\FormModel;
use Modules\Main\Models\Common;
use Modules\ModulesController;
use Modules\System\Http\Controllers\Common\SessionKey;
use Modules\System\Http\Requests\verifyFunction;

class ModelController extends ModulesController {
    use verifyFunction;
    private $login_unique;


    public function __construct(Request $request) {
        parent::__construct($request);
        $this->login_unique = SessionKey::HomeInfo;
    }

    public function list($access) {
        $uri = getURIByRoute($this->request)['uri'];

        $model = FormModel::query()->where('access_identification', $access)->first();
        if (!$model) return abort(404);

        $model['fields'] = $model['fields'] ? json_decode($model['fields'], true) : [];

        if ($model['data_source'] == "api") {

            $curldatas = json_decode(curl_request($model['data_source_api_url']), true);
            $model['data_source_field_mapping'] = str_replace("\r\n", "\n", $model['data_source_field_mapping']);
            $data_source_field_mapping = explode("\n", $model['data_source_field_mapping']);
            $data_source_field_mappings = [];
            foreach ($data_source_field_mapping as $v) {
                $temp = explode("=>", $v);
                if (count($temp) == 2) $data_source_field_mappings[$temp[0]] = $temp[1];
            }
            $list = $curldatas["data"];
            foreach ($list as $index => $v) {
//                $list[$index] = (object)$list[$index];
                foreach ($data_source_field_mappings as $key => $val) {
                    $list[$index][$key] = $v[$val];
                }
            }

        } else {
            $list = Common::query()->from("module_formtools_{$model['identification']}")->latest('id');
            if ($model['page_num'] > 0) {
                //$model['page_num']
                $list = $list->paginate(6);
            } else {
                $list = $list->get()->toArray();
            }
        }

        $param['model'] = $access;
        if (substr($uri, 0, 4) == 'api/') {
            return json_encode([
                'status' => 200,
                'msg' => 'success',
                'data' => [
                    'list' => $list,
                    'model' => $model,
                    'param' => $param,
                ],
            ], JSON_UNESCAPED_UNICODE);
        }
        return ModelView($model['list_template'] ?: 'list', [
            'list' => $list,
            'model' => $model,
            'param' => $param,
            'data_source' => $model['data_source']
        ]);
    }

    public function detail($access, $id) {
        $uri = getURIByRoute($this->request)['uri'];

        $model = FormModel::query()->where('access_identification', $access)->first();
        if (!$model) return abort(404);
        $pid = 'pid';//默认上级id
        //通过自定义的字段查询某个外键
        foreach (json_decode($model['fields'], true) as $fields) {
            if ($fields['foreign_key']) {
                $pid = $fields['identification'];
                break;
            }
        }
        $model['fields'] = $model['fields'] ? json_decode($model['fields'], true) : [];
        if ($model['data_source'] == "api") {

            $curldatas = json_decode(curl_request($model['data_source_api_url_detail'] . $id), true);
            $data = $curldatas["data"];
            $model['data_source_field_mapping'] = str_replace("\r\n", "\n", $model['data_source_field_mapping']);
            $data_source_field_mapping = explode('\n', $model['data_source_field_mapping']);
            $data_source_field_mappings = [];
            foreach ($data_source_field_mapping as $v) {
                $temp = explode("=>", $v);
                if (count($temp) == 2) $data_source_field_mappings[$temp[0]] = $temp[1];
            }

            $list = $curldatas['other'];

        } else {
            $data = Common::query()->from("module_formtools_{$model['identification']}")->where('id', $id)->first();
            if (!$data) return abort(404);
            $list = Common::query()->from("module_formtools_{$model['identification']}")
                ->where($pid, $data[$pid])
                ->whereNot('id', $id)
                ->latest('id')
                ->limit(10)
                ->get()
                ->toArray();
            $data['prev_id'] = Common::query()->from("module_formtools_{$model['identification']}")
                ->where($pid, $data[$pid])
                ->where('id', '<', $id)->limit(1)->value('id');
            $data['last_id'] = Common::query()->from("module_formtools_{$model['identification']}")
                ->where($pid, $data[$pid])
                ->where('id', '>', $id)->limit(1)->value('id');
        }


        $param['model'] = $access;
        $param['id'] = $id;


        if (substr($uri, 0, 4) == 'api/') {
            return json_encode([
                'status' => 200,
                'msg' => 'success',
                'data' => [
                    'data' => $data,
                    'list' => $list,
                    'model' => $model,
                    'param' => $param,
                ],
            ], JSON_UNESCAPED_UNICODE);
        }

        return ModelView($model['detail_template'] ?: 'detail', [
            'data' => $data,
            'list' => $list,
            'model' => $model,
            'param' => $param,
        ]);
    }

    public function handle($access) {
        $model = FormModel::query()->where('access_identification', $access)->first();
        if (!$model) return back();
        return ModelView($model['detail_template'] ?: 'handle', []);
    }
}
