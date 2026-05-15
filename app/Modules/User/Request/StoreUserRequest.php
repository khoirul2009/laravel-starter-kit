<?php

namespace App\Modules\User\Request;

use App\Modules\User\Dto\StoreUserDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'integer', Rule::in([0, 1])],
        ];
    }

    public function toDto(): StoreUserDto
    {
        $data = $this->validated();

        return new StoreUserDto(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            status: (int) $data['status'],
        );
    }
}
