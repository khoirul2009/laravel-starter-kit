# Laravel Clean Architecture

This project uses a modular clean architecture structure instead of placing everything directly in Laravel's default folders.

## Installation

### Run with Docker (GHCR image)

The CI workflow at `.github/workflows/docker-build-push.yml` builds the application image and pushes it to **GitHub Container Registry** on every push to `main` and every `v*.*.*` tag. To run it locally:

1. **Pull the image**

   ```bash
   docker pull ghcr.io/OWNER/REPO:latest
   ```

   Replace `OWNER/REPO` with the GitHub `owner/repository` slug (e.g. `your-org/laravel-observe`). If the package is private, run `docker login ghcr.io` first using a Personal Access Token with the `read:packages` scope.

2. **Prepare environment**

   ```bash
   cp .env.example .env
   # edit .env — set APP_KEY (php artisan key:generate after first up), DB_*, OPENOBSERVE_*
   export APP_IMAGE=ghcr.io/OWNER/REPO:latest
   ```

3. **Start the stack**

   ```bash
   docker compose up -d
   ```

   This launches the Laravel app (Octane + FrankenPHP) on `http://localhost:8000`.

4. **Run database migrations**

   ```bash
   docker compose exec laravel-app php artisan migrate
   ```

5. **Seed the database**

   ```bash
   docker compose exec laravel-app php artisan db:seed
   ```

The image tag scheme produced by CI:

| Trigger              | Tag(s)                              |
|----------------------|-------------------------------------|
| push to `main`       | `latest`, `main`, `sha-<shortsha>`  |
| tag `v1.2.3`         | `1.2.3`, `1.2`, `sha-<shortsha>`    |

## Backend Structure

```txt
app/
├── Modules/
│   ├── Auth/
│   │   ├── Controller/
│   │   │   └── LoginController.php
│   │   ├── Service/
│   │   │   └── AuthService.php
│   │   ├── Dto/
│   │   │   └── LoginDto.php
│   │   └── Request/
│   │       └── LoginRequest.php
│   │
│   ├── User/
│   │   ├── Controller/
│   │   │   └── UserController.php
│   │   ├── Service/
│   │   │   └── UserService.php
│   │   ├── Dto/
│   │   │   ├── StoreUserDto.php
│   │   │   └── UpdateUserDto.php
│   │   ├── Repository/
│   │   │   ├── UserRepository.php
│   │   │   └── UserRepositoryInterface.php
│   │   └── Request/
│   │       ├── StoreUserRequest.php
│   │       └── UpdateUserRequest.php
│   │
│   └── Dashboard/
│       ├── Controller/
│       │   └── DashboardController.php
│       └── Service/
│           └── DashboardService.php
│
└── Models/
    └── User.php
```

Architecture Rules
Controller must only handle HTTP request and response logic.
Controller must not contain business logic.
Controller calls Service.
Service contains business logic.
Service calls Repository for database operations.
Repository contains Eloquent query logic.
DTO is used to transfer validated request data from Controller to Service.
Request classes handle validation only.
Models stay in app/Models.
Routes should point to controllers inside app/Modules.
Use dependency injection for Service and Repository.
Use interfaces for repositories where useful.
Keep each module independent and easy to maintain.
Example Flow: Create User
UserController
→ StoreUserRequest
→ StoreUserDto
→ UserService
→ UserRepository
→ User Model

Layer Responsibilities
Controller

The controller is responsible for handling HTTP requests and returning HTTP responses.

Example responsibilities:

Receive request
Use Form Request validation
Convert validated data into DTO
Call Service
Return Inertia response, redirect, or JSON response

Controllers should not contain business logic.

Request

Request classes are responsible for validation only.

Example:

StoreUserRequest
UpdateUserRequest
LoginRequest
DTO

DTO classes are used to transfer validated data between layers.

Example:

StoreUserDto
UpdateUserDto
LoginDto

DTOs help keep the Service layer clean and prevent passing raw request data directly.

Service

Service classes contain application business logic.

Example responsibilities:

Create user
Update user
Delete user
Hash password
Prevent deleting the currently logged-in user
Handle application rules
Repository

Repository classes contain database query logic.

Example responsibilities:

Query users
Search users
Paginate users
Create user record
Update user record
Delete user record

Repositories use Eloquent models internally.

Model

Models stay inside:

app/Models

Example:

app/Models/User.php

Models should represent database entities and relationships.

User Module Flow
Create User
UserController@store
→ StoreUserRequest
→ StoreUserDto
→ UserService@create
→ UserRepository@create
→ User Model
Update User
UserController@update
→ UpdateUserRequest
→ UpdateUserDto
→ UserService@update
→ UserRepository@update
→ User Model
Delete User
UserController@destroy
→ UserService@delete
→ UserRepository@delete
→ User Model

```php
Route Example
use App\Modules\Auth\Controller\LoginController;
use App\Modules\Dashboard\Controller\DashboardController;
use App\Modules\User\Controller\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });

});

```
