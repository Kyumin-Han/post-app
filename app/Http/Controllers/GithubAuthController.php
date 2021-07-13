<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GithubAuthController extends Controller
{
    public function __construct() {
        $this->middleware(['guest']);
    }

    public function redirect() {
        return Socialite::driver('github')->redirect();
    }

    public function callback() {
        $user = Socialite::driver('github')->user();
        // dd($user);
        // DB에 사용자 정보를 저장한다
        // 이미 사용자의 정보가 DB에 저장되어 있으면 저장할 필요가 없다

        $user = User::firstOrCreate(['email'=>$user->getEmail()],
            [
            'password'=>Hash::make(Str::random(24)),
            'name'=>$user->getName()
        ]);

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
