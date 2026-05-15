<?php

namespace App\Modules\User\Repository;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function paginateSearch(?string $q, int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->when($q, function ($query, $q) {
                $query->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $attributes): User
    {
        return User::create($attributes);
    }

    public function update(User $user, array $attributes): User
    {
        $user->fill($attributes)->save();

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function countAll(): int
    {
        return User::count();
    }

    public function countByStatus(int $status): int
    {
        return User::where('status', $status)->count();
    }

    public function latest(int $limit = 5): Collection
    {
        return User::query()
            ->orderByDesc('created_at')
            ->take($limit)
            ->get(['id', 'name', 'email', 'status', 'created_at']);
    }
}
