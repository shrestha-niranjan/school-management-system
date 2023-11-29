<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreStudentRequest;

class StudentController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Student/Create');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return to_route('students.index');
    }

    public function edit(Student $student): Response
    {
        $data['isEdit'] = true;
        $data['item'] = $student;

        return Inertia::render('Student/Create', $data);
    }

    public function index(Request $request): Response
    {
        $paginationCount = $request->input('pagination_count', config('app.pagination'));

        $data['items'] = StudentResource::collection(
            Student::query()
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
                'header' => 'Address',
                'field' => 'address'
            ],
        ];

        return
            Inertia::render('Student/Index', $data);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Student::query()
            ->create($data + [
                'grade_id' => school_setting()->grade_id
            ]);

        return to_route('students.index');
    }

    public function update(StoreStudentRequest $request, Student $student): RedirectResponse
    {
        $data = $request->validated();

        $student->update($data);

        return to_route('students.index');
    }
}
