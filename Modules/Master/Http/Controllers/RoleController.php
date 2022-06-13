<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Role;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Yajra\DataTables\DataTables;

class RoleController extends AdminController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('master::role', $this->content);
    }

    public function datatable(Request $request)
    {
        $params = $request->post('datatable');
        $query = Role::query();
        return DataTables::of($query)->toJson();
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('master::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);

        if ($request->post('id') != null) {
            $request->validate([
                'id' => 'required'
            ]);
            $data = Role::where('id', $request->post('id'))
                ->update([
                    'name' => $request->post('name'),
                    'slug' => $request->post('slug'),
                    'description' => $request->post('description'),
                ]);

            return $this->responseJson(
                $data
            );
        } else {
            $insertId = Role::create([
                'name' => $request->post('name'),
                'slug' => $request->post('slug'),
                'description' => $request->post('description'),
            ])->id;
        }

        return $this->responseJson(
            $insertId
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('master::edit');
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
    public function destroy(Request $request)
    {
        $id = $request->post('id');
        $result = Role::destroy($id);
        return $this->responseJson(
            $result,
            ($result == 0) ? 'Gagal menghapus data' : 'Data berhasil dihapus',
            ($result == 0) ? 400 : 200,
            ($result == 0) ? 400 : 200
        );
    }
}
