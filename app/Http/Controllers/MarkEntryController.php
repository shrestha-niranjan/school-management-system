<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Course;
use App\Models\MarkEntry;
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
                ->where('grade_id', school_setting()->grade_id)
                ->get()
        );

        $data['students'] = Student::query()
            ->doesntHave('markEntries')
            ->get();

        return Inertia::render('MarkEntry/Create', $data);
    }

    public function destroy(MarkEntry $user): RedirectResponse
    {
        $user->delete();

        return to_route('mark-entry.index');
    }

    public function edit(Student $student): Response
    {
        $data['isEdit'] = true;
        $data['item'] = $student;

        $student->load('markEntries');

        $data['courses'] = CourseOptionResource::collection(
            Course::query()
              ->get()
        );

        $data['students'] = Student::query()
            ->get();

        return Inertia::render('MarkEntry/Edit', $data);
    }

    public function index(): Response
    {
        $data['items'] =  MarkEntryResource::collection(
            Student::query()
                ->where('grade_id', school_setting()->grade_id)
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
                    'student_id' => $data['student_id'],
                    'course_id' => $markEntry['course_id'],
                    'external' => $markEntry['external'],
                    'internal' => $markEntry['internal']
                ]);
        }

        return to_route('mark-entry.index');
    }

    public function update(UpdateMarkEntryRequest $request, Student $student): RedirectResponse
    {
        $data = $request->validated();

        foreach ($data['marks'] as $mark) {
            $student->markEntries()->where('course_id', $mark['course_id'])->exists() ?
            $student->markEntries()->where('course_id', $mark['course_id'])->update([
                'external' => $mark['external'],
                'internal' => $mark['internal']
            ]) : $student->markEntries()->create(
                $mark
            );
        }

        return to_route('mark-entry.index');
    }
}
