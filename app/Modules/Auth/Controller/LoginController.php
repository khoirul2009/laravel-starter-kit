<?php

namespace App\Modules\Auth\Controller;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Request\LoginRequest;
use App\Modules\Auth\Service\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function __construct(private readonly AuthService $auth) {}

    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        if (! $this->auth->attempt($request->toDto(), $request)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect or the account is inactive.',
            ]);
        }

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->auth->logout($request);

        return redirect()->route('login');
    }
}
