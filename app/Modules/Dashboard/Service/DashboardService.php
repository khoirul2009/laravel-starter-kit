<?php

namespace App\Modules\Dashboard\Service;

use App\Modules\User\Repository\UserRepositoryInterface;

class DashboardService
{
    public function __construct(private readonly UserRepositoryInterface $users) {}

    public function stats(): array
    {
        return [
            'total' => $this->users->countAll(),
            'active' => $this->users->countByStatus(1),
            'inactive' => $this->users->countByStatus(0),
            'latest' => $this->users->latest(5),
        ];
    }
}
