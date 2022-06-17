<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class MenuController extends AdminController
{

    public function index()
    {
        $this->content['features'] = $this->getFeatures();
        return view('system::menu', $this->content);
    }

    public function datatable(Request $request)
    {
        $params = $request->post('datatable');
        $query = Menu::query()->select('*', 'parent as pid')->with('parent');
        $query->where('manageable', true);
        if (isset($params['type']))
            $query->where('type', $params['type']);
        if (isset($params['parent']))
            $query->where('parent', $params['parent']);
        return DataTables::of($query)->toJson();
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'link_type' => 'required_if:type,menu',
            'endpoint' => 'required_if:link_type,endpoint',
            'link' => 'required_if:link_type,link',
            'feature' => 'required_if:link_type,feature'
        ]);

        $link = null;
        if ($data['type'] == 'menu') {
            switch ($data['link_type']) {
                case 'endpoint':
                    $link = $data['endpoint'];
                    break;
                case 'link':
                    $link = $data['link'];
                    break;
                case 'feature':
                    $link = $data['feature'];
                    break;
            };
        }

        $result = null;
        if ($request->post('id') != null) {
            $result = Menu::where('id', $request->post('id'))
                ->update([
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'icon' => $request->post('icon'),
                    'link' => $link,
                    'target' => $request->post('target')
                ]);
        } else {
            $result = Menu::insert([
                'parent' => -1,
                'name' => $data['name'],
                'type' => $data['type'],
                'icon' => $request->post('icon'),
                'link' => $link,
                'target' => $request->post('target')
            ]);
        }

        return $this->responseJson($result);
    }

    public function mapper()
    {
        $this->content['menus'] = $this->getMenu(true);
        $this->content['menus_unmapped'] = $this->getMenu(false);
        return view('system::menu_mapper', $this->content);
    }

    public function map(Request $request)
    {
        $main = $request->post('main');
        $side = $request->post('side');

        if (!is_null($main) && is_array($main)) {
            $parent = '';
            $order = 1;
            foreach ($main as $menu) {
                if ($parent != $menu['parent'])
                    $order = 1;
                Menu::where('id', $menu['id'])->update([
                    'parent' => $menu['parent'],
                    'type' => ($menu['parent'] == 0) ? 'label' : 'menu',
                    'order' => $order
                ]);
                $order++;
                $parent = $menu['parent'];
            }
        }

        if (!is_null($side) && is_array($main)) {
            $parent = '';
            $order = 1;
            foreach ($side as $menu) {
                if ($parent != $menu['parent'])
                    $order = 1;
                Menu::where('id', $menu['id'])->update([
                    'parent' => -1,
                    'type' => ($menu['parent'] == 0) ? 'label' : 'menu',
                    'order' => $order
                ]);
                $order++;
                $parent = $menu['parent'];
            }
        }

        return $this->responseJson($request->post());
    }

    public function source(Request $request)
    {
        $query = '%' . $request->get('q') . '%';
        $distinct = Menu::select('parent')
            ->distinct('parent')
            ->pluck('parent')
            ->toArray();
        $parents = Menu::select('id', 'name as text')
            ->where('manageable', 1)
            ->where('name', 'like', $query)
            ->whereIn('id', $distinct)
            ->get()
            ->toArray();

        return [
            'results' => array_merge(
                [[
                    'id' => '',
                    'text' => 'Semua'
                ]],
                $parents
            )
        ];
    }

    private function getFeatures()
    {
        $feature = [];
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            $name = $route->getName();
            if (
                !is_null($name) &&
                !preg_match('/ignition|telescope/', $name) &&
                !in_array($name, ['login', 'logout', 'authenticate', 'forgot'])
            ) array_push($feature, $name);
        }

        return $feature;
    }

    private function getMenu($mapped)
    {
        $menus = [];
        if (!$mapped) {
            $source = Menu::where('parent', -1)
                ->orWhere(function ($query) {
                    return $query->where('parent', 0)
                        ->where('type', 'menu');
                })
                ->orderBy('parent')
                ->orderBy('order')
                ->get()
                ->toArray();

            foreach ($source as $menu) {
                $menu['link'] = is_null($menu['link']) ? '#' : $menu['link'];
                $menus[] = $menu;
            }
        } else {
            $source = Menu::orderBy('parent')
                ->orderBy('order')
                ->get()
                ->toArray();

            foreach (array_filter($source, function ($item) {
                return $item['type'] == 'label' && $item['parent'] == 0;
            }) as $indexlabel => $label) {
                $label['sub'] = array_filter($source, function ($item) use ($label) {
                    return $item['type'] == 'menu' && $item['parent'] == $label['id'];
                });
                $label['link'] = is_null($label['link']) ? '#' : $label['link'];
                $menus[$indexlabel] = $label;

                foreach ($label['sub'] as $indexmain => $main) {
                    $main['sub'] = array_filter($source, function ($item) use ($main) {
                        return $item['type'] == 'menu' && $item['parent'] == $main['id'];
                    });
                    $main['link'] = is_null($main['link']) ? '#' : $main['link'];
                    $menus[$indexlabel]['sub'][$indexmain] = $main;

                    foreach ($main['sub'] as $indexsub => $sub) {
                        $sub['link'] = is_null($sub['link']) ? '#' : $sub['link'];
                        $menus[$indexlabel]['sub'][$indexmain]['sub'][$indexsub] = $sub;
                    }
                }
            }
        }

        return json_decode(json_encode($menus));
    }
}
