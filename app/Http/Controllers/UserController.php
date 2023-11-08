<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\GradeOptionResource;
use App\Http\Resources\RoleOptionResource;
use App\Models\Grade;

class UserController extends Controller
{
    public function create(): Response
    {
        $data['grades'] = GradeOptionResource::collection(
            Grade::all()
        );

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

        $data['grades'] = GradeOptionResource::collection(
            Grade::all()
        );

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
            ->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'grade_id' => $data['grade']
            ])
            ->assignRole(
                $data['role']
            );

        return to_route('users.index');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'grade_id' => $data['grade'],
            'password' => Hash::make($data['password'])
        ]);

        $user->syncRoles($data['role']);

        return to_route('users.index');
    }
}
