<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grade = Grade::query()
            ->where('name', 10)
            ->first();

        foreach ($this->names() as $name) {
            Course::query()
                ->firstOrCreate([
                    'name' => $name
                ], [
                    'grade_id' => $grade->id
                ]);
        }
    }

    public function names() : array
    {
        return [
            'English',
            'Nepali',
            'Math',
            'Science',
            'Social',
            'ECO',
            'Account',
        ];
    }
}
