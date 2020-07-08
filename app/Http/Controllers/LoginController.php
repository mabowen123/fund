<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('pages.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('root');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => '账号必填',
            'username.password' => '密码必填',
        ]);

        $data = $request->all(['username', 'password']);
        if (Auth::attempt($data)) {
            return redirect()->route('root');
        } else {
            session()->flash('danger', '账号密码错误');
            return redirect()->back();
        }
    }
}
