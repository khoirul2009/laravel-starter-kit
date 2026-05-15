<?php

namespace App\Modules\User\Dto;

class StoreUserDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly int $status,
    ) {}
}
