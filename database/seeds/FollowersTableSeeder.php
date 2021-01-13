<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;

        //获取除了用户ID为1 的所有用户ID
        $followers = $users->slice(1);
        $follower_ids = $followers->pluck('id')->toArray();

        //关注除了1号用户以外的用户
        $user->follow($follower_ids);

        //除了1号用户以外， 所有用户关注1号
        foreach ($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}
