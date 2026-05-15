<?php

namespace App\Modules\User\Service;

use App\Facades\OpenObserve;
use App\Models\User;
use App\Modules\User\Dto\StoreUserDto;
use App\Modules\User\Dto\UpdateUserDto;
use App\Modules\User\Repository\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    public function __construct(private readonly UserRepositoryInterface $repo) {}

    public function list(?string $q, int $perPage = 10): LengthAwarePaginator
    {
        return $this->repo->paginateSearch($q, $perPage);
    }

    public function create(StoreUserDto $dto): User
    {
        $user = $this->repo->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'status' => $dto->status,
        ]);

        OpenObserve::info('User created', [
            'created_user_id' => $user->id,
            'email' => $user->email,
        ]);

        return $user;
    }

    public function update(User $user, UpdateUserDto $dto): User
    {
        $attributes = [
            'name' => $dto->name,
            'email' => $dto->email,
            'status' => $dto->status,
        ];

        if (! empty($dto->password)) {
            $attributes['password'] = $dto->password;
        }

        $updated = $this->repo->update($user, $attributes);

        OpenObserve::info('User updated', [
            'target_user_id' => $updated->id,
            'changed' => array_keys($attributes),
        ]);

        return $updated;
    }

    public function delete(User $user, int $authUserId): void
    {
        if ($user->id === $authUserId) {
            throw new HttpException(403, 'You cannot delete your own account.');
        }

        $targetId = $user->id;
        $this->repo->delete($user);

        OpenObserve::info('User deleted', [
            'target_user_id' => $targetId,
        ]);
    }
}
