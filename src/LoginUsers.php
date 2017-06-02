<?php

namespace Voh\KmSocialite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * Trait LoginUsers
 * @package Voh\KmSocialite
 */
trait LoginUsers
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return mixed
     */
    public function oauth()
    {
        return Socialite::driver('km')->redirect();
    }

    /**
     * @return mixed
     */
    public function login()
    {
        /** @var \Laravel\Socialite\Two\User $rawUser */
        $rawUser = Socialite::driver('km')->user();

        $user = $this->performLogin($rawUser);

        Auth::login($user);

        return redirect(session('url.intended', $this->redirectPath()));
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    private function redirectPath()
    {
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }
}