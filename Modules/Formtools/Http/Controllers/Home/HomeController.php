<?php

namespace Modules\Formtools\Http\Controllers\Home;

use Illuminate\Contracts\Support\Renderable;
use Modules\ModulesController;

class HomeController extends ModulesController {
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        echo "Modules\Formtools\Http\Controllers\Home";
        return view("formtools::home.index.index",[]);
    }

}
