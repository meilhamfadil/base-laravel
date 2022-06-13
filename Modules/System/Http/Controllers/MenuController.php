<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Menu;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MenuController extends AdminController
{

    public function index()
    {
        return view('system::menu', $this->content);
    }

    public function datatable(Request $request)
    {
        $params = $request->post('datatable');
        $query = Menu::query();
        if (isset($params['type']))
            $query->where('type', 'like', '%' . $params['type'] . '%');
        return DataTables::of($query)->toJson();
    }
}
