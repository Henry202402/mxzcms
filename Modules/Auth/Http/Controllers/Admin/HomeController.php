<?php

namespace Modules\Auth\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Modules\ModulesController;

class HomeController extends ModulesController {
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        return redirect(url("admin/auth/group/list"));
    }

}
