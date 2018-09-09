<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/blog';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    public function redirectTo($provider)
    {
        session(['provider' => $provider]);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleSocialCallback()
    {
        $provider = session('provider');
        $user = Socialite::driver($provider)->user();
        $email = $user->getEmail();
        $exists = User::where('email', $email)->first();

        if($exists){
            Auth::loginUsingId($exists->id, true);
            return redirect('/profile');
        }else{

            $newUser = new User();
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->avatar = "";
            $newUser->avatar_url = $user->getAvatar();
            $newUser->password =  md5(time());
            if($newUser->save()){
                Auth::loginUsingId($newUser->id, true);
            }
            return redirect('/profile');
        }

    }

}
