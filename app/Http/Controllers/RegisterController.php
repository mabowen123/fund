<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function show()
    {
        return view('pages.register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users|max:16',
            'nickname' => 'required|string|max:16',
            'password' => 'required|confirmed|string|min:6|max:255',
            'password_confirmation' => 'required'
        ], [
            'username.unique' => '用户名已被注册',
            'username.max' => '超过最大长度',
            'username.required' => '用户名必填',
            'nickname.required' => '昵称必填',
            'nickname.max' => '超过最大长度',
            'password.min' => '密码要超过8位',
            'password.confirmed' => '密码不一致'
        ]);

        $data = $request->all('username', 'nickname', 'password');
        $user = User::create($data);
        Auth::login($user);
        return redirect(route('root'));
    }
}
