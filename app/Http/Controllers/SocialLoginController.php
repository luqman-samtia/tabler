<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\SocialLogin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class SocialLoginController extends Controller
{
    public function toProvider($driver){
        return Socialite::driver('github')->redirect();
    }
    public function handleCallback($driver){
        $user = Socialite::driver('github')->user();

        $user_account =SocialLogin::where('provider',$driver)->where('provider_id',$user->getId())->first();

        if ($user_account) {
            Auth::login($user_account->user);

            session()->regenerate();
            return redirect()->route('home');
        }

        $db_user = User::where('email',$user->getEmail())->first();
        if ($db_user) {
            SocialLogin::create([
                'provider'=> $driver,
                'provider_id'=> $user->getId(),
                'user_id'=> $db_user->id,
            ]);
        }else{
            $db_user = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt('password'),
            ]);
            SocialLogin::create([
                'provider'=> $driver,
                'provider_id'=> $user->getId(),
                'user_id'=> $db_user->id,
            ]);
        }
        Auth::login($db_user);

        session()->regenerate();
        return redirect()->route('home');
    }
}
