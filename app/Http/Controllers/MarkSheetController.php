<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MarkSheetController extends Controller
{
    public function __invoke(User $student)
    {
        $student->load('markEntries.course');

        return $student;
    }
}
