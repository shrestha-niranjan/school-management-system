<?php

namespace App\Http\Controllers;

use App\Models\Student;

class MarkSheetController extends Controller
{
    public function __invoke(Student $student)
    {
        $student->load('markEntries.course');

        return $student;
    }
}
