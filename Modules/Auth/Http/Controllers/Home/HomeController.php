<?php

namespace Modules\Auth\Http\Controllers\Home;

use Illuminate\Contracts\Support\Renderable;
use Modules\ModulesController;

class HomeController extends ModulesController {
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        echo "Modules\Auth\Http\Controllers\Home";
        return view("auth::home.index.index",[]);
    }

}
