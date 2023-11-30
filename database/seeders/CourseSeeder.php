<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->names() as $name) {
            Course::query()
                ->firstOrCreate([
                    'name' => $name
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
        ];
    }
}
