<?php

namespace App\Modules\User\Repository;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function paginateSearch(?string $q, int $perPage = 10): LengthAwarePaginator;

    public function find(int $id): ?User;

    public function create(array $attributes): User;

    public function update(User $user, array $attributes): User;

    public function delete(User $user): void;

    public function countAll(): int;

    public function countByStatus(int $status): int;

    public function latest(int $limit = 5): \Illuminate\Database\Eloquent\Collection;
}
