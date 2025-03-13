<?php

namespace Modules\Member\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Modules\ModulesController;

class HomeController extends ModulesController {
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = "账号管理";
        $pageData['subtitle'] = "账号管理子标题";

        return view("member::admin.index.index",[
            "pageData" =>$pageData
        ]);

    }

}
