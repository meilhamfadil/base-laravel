<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\AdminController;
use App\Models\Feature;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;

class FeatureController extends AdminController
{
    public function index()
    {
        return view('system::feature', $this->content);
    }

    public function datatable(Request $request)
    {
        $params = $request->post('datatable');
        $query = Feature::query();
        return DataTables::of($query)->toJson();
    }

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
            $data = Feature::where('id', $request->post('id'))
                ->update([
                    'name' => $request->post('name'),
                    'slug' => $request->post('slug'),
                ]);

            return $this->responseJson(
                $data
            );
        } else {
            $insertId = Feature::create([
                'name' => $request->post('name'),
                'slug' => $request->post('slug'),
            ])->id;
        }

        return $this->responseJson(
            $insertId
        );
    }

    public function destroy(Request $request)
    {
        $id = $request->post('id');
        $result = Feature::destroy($id);
        return $this->responseJson(
            $result,
            ($result == 0) ? 'Gagal menghapus data' : 'Data berhasil dihapus',
            ($result == 0) ? 400 : 200,
            ($result == 0) ? 400 : 200
        );
    }
}
