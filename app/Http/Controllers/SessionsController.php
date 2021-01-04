<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create() {
        return view('sessions/create');
    }

    public function store(Request $request) {
        $credentails = $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt($credentails, $request->has('remember'))) {
            session()->flash('success', '欢迎回来');
            return redirect()->route('users.show', [Auth::user()]);
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
