<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create() {
        return view('sessions/create');
    }

    public function store(Request $request) {
        $credentails = $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt($credentails, $request->has('remember'))) {
            if (Auth::user()->activation) {
                session()->flash('success', '欢迎回来');
                $fallback = route('users.show', [Auth::user()]);
                return redirect()->intended($fallback);
            } else {
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活');
                return redirect('/');
            }
        } else {
            session()->flash('danger', '邮箱和密码不对');
            return redirect()->back()->withInput();
        }

    }

    public function destroy() {
        Auth::logout();
        session()->flash('success', '您已成功退出');
        return redirect('login');
    }
}
