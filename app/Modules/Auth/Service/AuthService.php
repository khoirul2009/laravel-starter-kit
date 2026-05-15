<?php

namespace App\Modules\Auth\Service;

use App\Facades\OpenObserve;
use App\Modules\Auth\Dto\LoginDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function attempt(LoginDto $dto, Request $request): bool
    {
        $credentials = [
            'email' => $dto->email,
            'password' => $dto->password,
            'status' => 1,
        ];

        if (! Auth::attempt($credentials, $dto->remember)) {
            OpenObserve::warning('Login failed', ['email' => $dto->email]);
            return false;
        }

        $request->session()->regenerate();

        OpenObserve::info('User logged in', ['auth_user_id' => Auth::id()]);

        return true;
    }

    public function logout(Request $request): void
    {
        $previousId = Auth::id();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        OpenObserve::info('User logged out', ['auth_user_id' => $previousId]);
    }
}
