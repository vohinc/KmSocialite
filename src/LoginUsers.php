<?php

namespace Voh\KmSocialite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Voh\KmSocialite\Exceptions\DenyException;

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

        try {
            $user = $this->performLogin($rawUser);

            Auth::login($user);

            return response()->json($user)->header('Authorization', "Bearer: {$user->getApiToken()}");
        } catch (DenyException $ex) {
            return response()->json([], 404);
        }
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

        return response()->json([], 201);
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
