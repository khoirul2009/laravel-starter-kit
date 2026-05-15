<?php

namespace App\Modules\User\Request;

use App\Modules\User\Dto\UpdateUserDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'integer', Rule::in([0, 1])],
        ];
    }

    public function toDto(): UpdateUserDto
    {
        $data = $this->validated();

        return new UpdateUserDto(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            status: (int) $data['status'],
        );
    }
}
