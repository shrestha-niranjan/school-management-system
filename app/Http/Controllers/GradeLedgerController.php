<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeLedgerController extends Controller
{
    public function index(Request $request)
    {
        $data['students'] = Student::query()
            ->where('grade_id', school_setting()->grade_id)
            ->with('markEntries')
            ->get()
        ;

        $data['courses'] = Course::query()
            ->where('grade_id', school_setting()->grade_id)
            ->get();

        return
            Inertia::render('GradeLedger/Index', $data);
    }
}
