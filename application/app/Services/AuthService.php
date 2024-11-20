<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthService
{

    public function login(array $credentials, bool $ajax = false): bool|string
    {
        $logged = Auth::attempt($credentials);
        if(!$logged) return false;

        if(!$ajax){
            Session::regenerate();
            return true;
        }

        return $this->getCurrentToken();
    }

    public function getCurrentToken(): string
    {
        $token = Session::get('CURRENT_ACCESS_TOKEN',Auth::user()->currentAccessToken()?->plainTextToken);
        if(!$token) $token = Auth::user()->createToken(config('app.name'))->plainTextToken;

        Session::put('CURRENT_ACCESS_TOKEN', $token);

        return $token;
    }

    public function logout(): bool
    {
        Auth::user()->tokens()->delete();
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();

        return true;
    }

}
