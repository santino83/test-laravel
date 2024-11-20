<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\View\View;

class HomepageController extends Controller
{

    public function __construct(protected AuthService $authService)
    {
    }

    public function index(): View
    {

        $token = $this->authService->getCurrentToken();

        return view('homepage', ['token' => $token]);
    }

}
