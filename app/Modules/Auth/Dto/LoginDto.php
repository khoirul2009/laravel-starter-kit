<?php

namespace App\Modules\Auth\Dto;

class LoginDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly bool $remember = false,
    ) {}
}
