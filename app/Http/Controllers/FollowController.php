<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user) {
        //You cannot follow yourself
        if ($user->id === auth()->user()->id) {
            return back()->with('error','are you that desperate to follow yourself 🤣');
        }

        //you cannot follow someone more than once
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        if ($existCheck) {
            return back()->with('error', "you are already following {$user->username}");
        }


        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        return back()->with('success', "you are now following {$user->username}");
    }

    public function removeFollow(User $user) {
        Follow::where([['user_id','=',auth()->user()->id],['followeduser','=', $user->id]])->delete();
        return back()->with('success', "you are no longer following {$user->username}");
    }
}
