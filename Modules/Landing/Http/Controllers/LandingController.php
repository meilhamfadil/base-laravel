<?php

namespace Modules\Landing\Http\Controllers;

use App\DataTables\MenuDataTable;
use App\Http\Controllers\BaseController;
use App\Models\Menu;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LandingController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->content['list'] = [
            ['Apple'],
            ['Banana'],
            ['Cherry']
        ];
        return view('landing::index', $this->content);
    }

    public function datatable(Request $request)
    {
        $filter = $request->post('datatable');
        $query = Menu::query();
        if (isset($filter['tipe']))
            $query->where('type', 'like', '%' . $filter['tipe'] . '%');
        return DataTables::of($query)->toJson();
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('landing::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('landing::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('landing::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
