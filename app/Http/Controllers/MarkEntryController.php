<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Course;
use App\Models\MarkEntry;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\MarkEntryResource;
use App\Http\Resources\RoleOptionResource;
use App\Http\Requests\StoreMarkEntryRequest;
use App\Http\Resources\CourseOptionResource;
use App\Http\Requests\UpdateMarkEntryRequest;

class MarkEntryController extends Controller
{
    public function create(): Response
    {
        $data['courses'] = CourseOptionResource::collection(
            Course::query()
              ->get(['id', 'name'])
        );

        $data['students'] = User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Student');
            })
            ->get();

        return Inertia::render('MarkEntry/Create', $data);
    }

    public function destroy(MarkEntry $user): RedirectResponse
    {
        $user->delete();

        return to_route('users.index');
    }

    public function edit(MarkEntry $user): Response
    {
        $user->load(['roles:roles.id,roles.name']);

        $data['isEdit'] = true;
        $data['item'] = $user;

        $data['roles'] = RoleOptionResource::collection(
            Role::query()
              ->whereNotIn('name', ['Super Admin'])
              ->get(['id', 'name'])
        );

        return Inertia::render('MarkEntry/Create', $data);
    }

    public function index(Request $request): Response
    {
        $paginationCount = $request->input('pagination_count', config('app.pagination'));

        $data['items'] = MarkEntryResource::collection(
            MarkEntry::query()
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
                'MarkEntry/Index',
                $data
            );
    }

    public function store(StoreMarkEntryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        MarkEntry::query()
            ->create($data)
            ->assignRole(
                $data['role']
            );

        return to_route('users.index');
    }

    public function update(UpdateMarkEntryRequest $request, MarkEntry $user): RedirectResponse
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
