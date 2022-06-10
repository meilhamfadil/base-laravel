<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class BaseController extends Controller
{

    protected $content = [];

    public function __construct()
    {
        $this->content['app_menu'] = $this->getMenu();
    }

    private function getMenu()
    {
        return Menu::where('deleted_at', null)
            ->orderBy('parent')
            ->orderBy('order')
            ->get()
            ->toArray();
    }
}
