<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AdminController extends Controller
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $content = [];

    public function __construct()
    {
        $this->content['app_menu'] = $this->getMenu();
        $this->content['user'] = auth()->user();
    }

    private function getMenu()
    {
        $result = [];
        $source =  Menu::where('deleted_at', null)
            ->orderBy('parent')
            ->orderBy('order')
            ->get()
            ->toArray();

        // Get Label Menu
        $menus = array_filter($source, function ($item) {
            return $item['type'] == 'label' && $item['parent'] == 0;
        });

        // Get Main Menu
        foreach ($menus as $indexlabel => $label) {
            $label['sub'] = array_filter($source, function ($item) use ($label) {
                return $item['parent'] == $label['id'];
            });
            $label['name'] = strtoupper($label['name']);
            $label['padding'] = ($indexlabel == 0) ? 'padding: 0rem 1rem .5rem !important;' : '';
            $menus[$indexlabel] = $label;

            // Get Sub Menu
            foreach ($label['sub'] as $indexmain => $main) {
                $main['sub'] = array_filter($source, function ($item) use ($main) {
                    return $item['parent'] == $main['id'];
                });
                $activemain = trim(request()->path(), '/') == trim($main['link'], '/');
                $activesub = !empty(array_filter($main['sub'], function ($item) {
                    return trim(request()->path(), '/') == trim($item['link'], '/');
                }));
                $main['active'] = $activemain || $activesub  ? 'active' : '';
                $main['open'] = $activesub ? 'menu-open' : '';
                $main['subclass'] = empty($main['sub']) ? '' : 'has-treeview';
                $menus[$indexlabel]['sub'][$indexmain] = $main;

                // Get Active Sub
                foreach ($main['sub'] as $indexsub => $sub) {
                    $sub['active'] = trim(request()->path(), '/') == trim($sub['link'], '/') ? 'active' : '';
                    $menus[$indexlabel]['sub'][$indexmain]['sub'][$indexsub] = $sub;
                }
            }
        }

        return json_decode(json_encode($menus));
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
