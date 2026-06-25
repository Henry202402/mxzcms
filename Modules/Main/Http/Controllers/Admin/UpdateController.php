<?php

namespace Modules\Main\Http\Controllers\Admin;

use App\Support\Update\UpdateResponseFactory;
use Modules\ModulesController;

class UpdateController extends ModulesController {

    public function updateCmsVersion(){
        $all = $this->normalizeUpdatePayload($this->request->all());
        if ($error = $this->validateUpdatePayload($all)) {
            return $error;
        }
        $UPDATECMS = new \Modules\Main\Libs\UPDATECMS();
        if ($all["identification"]=="cms"){
            $res = $UPDATECMS->cmsAction($all);
            return $res;
        }else{
            $res = $UPDATECMS->appAction($all);
            return $res;
        }

        return [];
    }

    public function checklimit(){
        $all = $this->normalizeUpdatePayload($this->request->all());
        if ($all["identification"] === '' || $all["cloudtype"] === '') {
            return UpdateResponseFactory::contextual(UpdateResponseFactory::error('更新参数不完整', [
                'reason_code' => 'invalid_request',
            ], 400), 'app', 'checklimit', [
                'identification' => $all["identification"],
                'cloudtype' => $all["cloudtype"],
            ]);
        }
        $versionLimit = session()->get("versionLimit") ?: [];
        $data  = $versionLimit[$all["cloudtype"]."_".$all["identification"]] ?? '';
        if($data){
            return [
                'status'=>0,
                'msg' => $data
            ];
        }
        return [
            'status'=>200,
            'msg' => ""
        ];

    }

    private function normalizeUpdatePayload(array $all): array
    {
        $all["identification"] = trim((string) ($all["identification"] ?? ''));
        $all["action"] = trim((string) ($all["action"] ?? ''));
        $all["cloudtype"] = trim((string) ($all["cloudtype"] ?? ''));
        return $all;
    }

    private function validateUpdatePayload(array $all): ?array
    {
        $target = $all["identification"] === 'cms' ? 'cms' : 'app';
        $context = [
            'identification' => $all["identification"],
            'cloudtype' => $all["cloudtype"],
        ];

        if ($all["identification"] === '' || $all["action"] === '') {
            return UpdateResponseFactory::contextual(UpdateResponseFactory::error('更新参数不完整', [
                'reason_code' => 'invalid_request',
            ], 400), $target, 'dispatch', $context);
        }

        if ($all["identification"] !== 'cms' && $all["cloudtype"] === '') {
            return UpdateResponseFactory::contextual(UpdateResponseFactory::error('缺少更新类型参数', [
                'reason_code' => 'invalid_cloudtype',
            ], 400), $target, 'dispatch', $context);
        }

        return null;
    }
}
