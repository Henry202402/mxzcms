<?php

namespace Modules\Main\Http\Controllers\Home;

use Illuminate\Http\Request;
use Modules\Formtools\Models\FormModel;
use Modules\ModulesController;
use Modules\System\Http\Controllers\Common\SessionKey;
use Modules\System\Http\Requests\verifyFunction;

class HomeController extends ModulesController {
    use verifyFunction;
    private $login_unique;


    public function __construct(Request $request) {
        parent::__construct($request);
        $this->login_unique = SessionKey::HomeInfo;
    }

    public function index() {
        $module = verifyFunction::domainGetBindModule($this->request);
        $this->request->offsetSet('module_name_first', $module);
        $view = $this->GetModuleSetIndex();
        if ($view) {
            return $view;
        } else {
            $userInfo = session(SessionKey::HomeInfo);
            $models = FormModel::query()->where("show_home_page","yes")->get();
//            dump($models);
            return HomeView('index.index', compact("models","userInfo"));
        }
    }


}