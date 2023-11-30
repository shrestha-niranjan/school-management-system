<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Course;
use App\Models\MarkEntry;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Resources\MarkEntryResource;
use App\Http\Requests\StoreMarkEntryRequest;
use App\Http\Resources\CourseOptionResource;
use App\Http\Requests\UpdateMarkEntryRequest;
use App\Models\Student;

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

    public function edit(User $student): Response
    {
        $data['isEdit'] = true;
        $data['item'] = $student;

        $student->load('markEntries');

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
            Student::query()
                ->whereHas('markEntries')
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

    public function update(UpdateMarkEntryRequest $request, User $student): RedirectResponse
    {
        $data = $request->validated();

        foreach ($data['marks'] as $mark) {
            $student->markEntries()->where('course_id', $mark['course_id'])->update([
                'external' => $mark['external'],
                'internal' => $mark['internal']
            ]);
        }

        return to_route('mark-entry.index');
    }
}
