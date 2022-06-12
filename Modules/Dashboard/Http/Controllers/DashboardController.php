<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\AdminController;

class DashboardController extends AdminController
{
    public function index()
    {
        return view(
            'dashboard::index',
            $this->content
        );
    }
}
