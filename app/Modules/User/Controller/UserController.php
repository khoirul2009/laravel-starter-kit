<?php

namespace App\Modules\User\Controller;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\User\Request\StoreUserRequest;
use App\Modules\User\Request\UpdateUserRequest;
use App\Modules\User\Service\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(private readonly UserService $users) {}

    public function index(Request $request): Response
    {
        $q = $request->string('q')->toString() ?: null;

        return Inertia::render('Users/Index', [
            'users' => $this->users->list($q, 10),
            'filters' => ['q' => $q],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->users->create($request->toDto());

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user): Response
    {
        return Inertia::render('Users/Show', [
            'user' => $user->only(['id', 'name', 'email', 'status', 'created_at', 'updated_at']),
        ]);
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => $user->only(['id', 'name', 'email', 'status']),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->users->update($user, $request->toDto());

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->users->delete($user, (int) $request->user()->id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
