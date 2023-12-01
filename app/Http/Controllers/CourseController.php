<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();

        Course::create($data + [
            'grade_id' => school_setting()->grade_id
        ]);

        return to_route('settings.edit');
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->all();

        $course->update($data);

        return to_route('settings.edit');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return to_route('settings.edit');
    }
}
