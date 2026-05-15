<?php

namespace App\Modules\Dashboard\Controller;

use App\Http\Controllers\Controller;
use App\Modules\Dashboard\Service\DashboardService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Index', [
            'stats' => $this->dashboard->stats(),
        ]);
    }
}
