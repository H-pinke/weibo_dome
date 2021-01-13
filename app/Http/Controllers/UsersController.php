<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
           'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
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
        $statuses = $user->statuses()
                            ->orderBy('created_at', 'desc')
                            ->paginate(30);
        return view('users/show', compact('user', 'statuses'));
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
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
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

    /**
     * 确认邮箱
     * @param $token
     */
    public function confirmEmail($token) {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activation_token = null;
        $user->activation = true;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    /**
     * 发送邮件
     * @param $user
     */
    public function sendEmailConfirmationTo($user) {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = '936315305@qq.com';
        $name = 'huyaohua';
        $to = $user->email;
        $subject = '感谢注册 Weibo 应用！请确认你的邮箱。';
        Mail::send($view, $data, function ($message) use($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    //关注的人
    public function followings(User $user) {
        $users = $user->followings()->paginate(10);
        $title = $user->name . '关注的人';
        return view('users.show_follow', compact('users','title'));
    }

    //粉丝
    public function followers(User $user) {
        $users = $user->followers()->paginate(10);
        $title = $user->name . '的粉丝';
        return view('users.show_follow', compact('users','title'));
    }
}
