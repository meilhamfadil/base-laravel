<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('auth::login');
    }

    public function authenticate(Request $request)
    {
        $credential = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credential)) {
            $request->session->regenerate();
            return $this->responseJson();
        }

        return $this->responseJson(
            null,
            'Failed Login',
            401,
            401
        );
    }

    public function forgot(Request $request)
    {
        $credential = $request->validate([
            'email' => ['required', 'email']
        ]);

        $candidate = User::where('email', $request->post('email'))->first();

        return $this->responseJson(
            $credential,
            !empty($candidate) ? 'Do Forgot' : 'Stop',
            !empty($candidate) ? 200 : 404,
            !empty($candidate) ? 200 : 404
        );
    }
}
