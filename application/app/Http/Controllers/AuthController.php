<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService)
    {
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Login entrypoint
     *
     * @param LoginRequest $request
     * @return RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
    {

        $credentials = $request->getCredentials();

        $success = $this->authService->login($credentials);
        if ($success) return redirect()->route('homepage');

        return back()->withErrors([
            'username' => 'Credenziali non valide.',
        ]);
    }

    /**
     * Logout entrypoint
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return redirect()->route('login');
    }

}
