<?php

namespace Modules\Formtools\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Modules\ModulesController;

class HomeController extends ModulesController {
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        echo "Modules\Formtools\Http\Controllers\Api";
    }

}
