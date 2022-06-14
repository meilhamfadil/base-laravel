<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Feature;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FeatureController extends AdminController
{
    public function index()
    {
        $this->content['roles'] = Role::all();
        return view('system::feature', $this->content);
    }

    public function map(Request $request)
    {
        Feature::where('role_id', $request->post('role_id'))->delete();
        $feature = array_map(function ($item) use ($request) {
            return [
                'role_id' => $request->post('role_id'),
                'route_name' => $item
            ];
        }, $request->post('features'));
        Feature::insert($feature);
        return $this->responseJson(null, 'Pemetaan fitur telah tersimpan');
    }

    public function source($role)
    {
        $feature = [];

        $mapped = Feature::select('route_name')->where('role_id', $role)->pluck('route_name')->toArray();
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            $name = $route->getName();
            if (
                !is_null($name) &&
                !preg_match('/ignition|telescope/', $name) &&
                !in_array($name, ['login', 'logout', 'authenticate', 'forgot'])
            ) array_push($feature, (object)[
                'name' => $name,
                'selected' => in_array($name, $mapped)
            ]);
        }

        return $this->responseJson(
            [
                'options' => $feature,
                'origin' => $mapped
            ]
        );
    }
}
