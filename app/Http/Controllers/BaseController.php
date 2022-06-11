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

    protected function responseJson(
        $data = null,
        $message = 'Success',
        $code = 200,
        $httpCode = 200
    ) {
        return response(
            [
                'code' => $code,
                'message' => $message,
                'data' => $data
            ],
            $httpCode
        )->header('Content-type', 'application/json');
    }
}
