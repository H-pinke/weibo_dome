<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
           'except' => ['show', 'create', 'store', 'index']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create() {
        return view('users/create');
    }

    /**
     * 用户列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $users = User::paginate(10);
        return view('users/index', compact('users'));
    }

    public function show(User $user) {
        return view('users/show', compact('user'));
    }

    public function store(Request $request) {
        $this->validate($request,[
            'name'     => 'required|max:50',
            'email'    => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段旅程');
        return redirect()->route('users.show', [$user]);
    }

    public function edit(User $user) {
        $this->authorize('update', $user);
        return view('users/edit', compact('user'));
    }

    public function update(User $user, Request $request) {
        $this->authorize('update', $user);

        $this->validate($request,[
            'name'     => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $data = [];
        $data['name'] = $request->name;
        $request->password && $data['password'] = bcrypt($request->password);

        $user->update($data);
        session()->flash('success', '更新资料成功');
        return redirect()->route('users.show', [$user]);
    }

    /**
     * 删除用户
     * @param User $user
     */
    public function destroy(User $user) {
        $user->delete();
        $this->authorize('destroy', $user);
        session()->flash('success', '成功删除用户');
        return back();
    }
}
