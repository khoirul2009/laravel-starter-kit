<?php

namespace App\Modules\Auth\Request;

use App\Modules\Auth\Dto\LoginDto;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    public function toDto(): LoginDto
    {
        return new LoginDto(
            email: (string) $this->validated('email'),
            password: (string) $this->validated('password'),
            remember: (bool) $this->boolean('remember'),
        );
    }
}
