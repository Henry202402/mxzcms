<?php

namespace Modules\Main\Http\Controllers\Home;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Support\I18n\ThemeTranslator;
use Modules\Formtools\Models\FormModel;
use Modules\Formtools\Models\FormPage;
use Modules\Formtools\Http\Controllers\Home\PageController as FormPageController;
use Modules\Main\Services\ServiceModel;
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
        if ($module) {
            $this->request->offsetSet('module_name_first', $module);
            $view = $this->GetModuleSetIndex();
            if ($view) {
                return $view;
            }
        }

        $homepage = FormPage::resolveHomepage();
        if ($homepage) {
            return app(FormPageController::class)->renderPage($homepage, false, [
                'publicUrl' => url('/'),
            ]);
        }

        $view = $this->GetModuleSetIndex();
        if ($view) {
            return $view;
        } else {
            $userInfo = session(SessionKey::HomeInfo);
            $models = FormModel::query()->where("show_home_page","yes")->orderBy("home_page_sort")->get();
//            dump($models);
            return HomeView('index.index', compact("models","userInfo"));
        }
    }

    public function lang()
    {
       if (!ThemeTranslator::isMultilingualEnabled()) {
           session()->put('homelang', ThemeTranslator::defaultLocale());
           Cache::put("homelangList", null);
           return back();
       }

       $all = $this->request->all();
       $langList = ServiceModel::getLangList();
       $lang = trim((string) ($all['lang'] ?? ''));
       if (!array_key_exists($lang, $langList)) {
           $lang = ThemeTranslator::defaultLocale();
       }

       session()->put('homelang', $lang);
       Cache::put("homelangList", null);
       return back();
    }


}
