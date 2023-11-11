<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MarkLedgerController extends Controller
{
    public function index(Request $request)
    {
        $data['students'] = User::query()
            ->with('markEntries')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Student');
            })
            ->get()
        ;

        $data['courses'] = Course::query()
            ->pluck('name', 'id');

        return
            Inertia::render('MarkLedger/Index', $data);
    }
}
