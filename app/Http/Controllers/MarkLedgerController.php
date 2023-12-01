<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class MarkLedgerController extends Controller
{
    public function index(Request $request)
    {
        $data['students'] = Student::query()
            ->with('markEntries')
            ->get()
        ;

        $data['courses'] = Course::query()
            ->get();

        return
            Inertia::render('MarkLedger/Index', $data);
    }
}
