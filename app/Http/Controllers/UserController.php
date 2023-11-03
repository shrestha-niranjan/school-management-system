<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\RoleOptionResource;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function create(): Response
    {
        $data['roles'] = RoleOptionResource::collection(
            Role::query()
              ->whereNotIn('name', ['Super Admin'])
              ->get(['id', 'name'])
        );

        return Inertia::render('User/Create', $data);
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return to_route('users.index');
    }

    public function edit(User $user): Response
    {
        $user->load(['roles:roles.id,roles.name']);

        $data['isEdit'] = true;
        $data['item'] = $user;

        $data['roles'] = RoleOptionResource::collection(
            Role::query()
              ->whereNotIn('name', ['Super Admin'])
              ->get(['id', 'name'])
        );

        return Inertia::render('User/Create', $data);
    }

    public function index(Request $request): Response
    {
        $paginationCount = $request->input('pagination_count', config('app.pagination'));

        $data['items'] = UserResource::collection(
            User::query()
                ->withoutSuperAdmin()
                ->with('roles')
                ->latest()
                ->paginate($paginationCount)
                ->withQueryString()
        );

        $data['columns'] = [
            [
                'header' => 'Name',
                'field' => 'name'
            ],
            [
                'header' => 'Email',
                'field' => 'email'
            ],
        ];

        return
            Inertia::render(
                'User/Index',
                $data
            );
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        User::query()
            ->create($data)
            ->assignRole(
                $data['role']
            );

        return to_route('users.index');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (is_null($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        $user->syncRoles($data['role']);

        return to_route('users.index');
    }
}
