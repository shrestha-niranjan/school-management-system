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

        return to_route('mark-entry.index');
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
        // $data['students'] = User::query()
        //     ->with('markEntries')
        //     ->whereHas('roles', function ($query) {
        //         $query->where('name', 'Student');
        //     })
        //     ->get()
        // ;

        // $data['courses'] = Course::query()
        //     ->pluck('name', 'id');

        $data['items'] =  MarkEntryResource::collection(
            User::query()
                ->whereHas('markEntries')
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'Student');
                })
                ->paginate()
        );

        $data['columns'] =  [
            [
                'header' => 'Student',
                'field' => 'student'
            ],
            [
                'header' => 'Grade',
                'field' => 'grade'
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

        foreach ($data['marks'] as $markEntry) {
            MarkEntry::query()
                ->create([
                    'user_id' => $data['student_id'],
                    'course_id' => $markEntry['course_id'],
                    'external' => $markEntry['external'],
                    'internal' => $markEntry['internal']
                ]);
        }

        return to_route('mark-entry.index');
    }

    public function update(UpdateMarkEntryRequest $request, MarkEntry $user): RedirectResponse
    {
        $data = $request->validated();

        if (is_null($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        $user->syncRoles($data['role']);

        return to_route('mark-entry.index');
    }
}
