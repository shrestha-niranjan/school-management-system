<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SchoolSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolSetting::query()
            ->firstOrCreate([
                'name' => 'Shree Bhimeshwor Rudra Secondary School',
                'grade_id' => 10,
                'established_at' => 2038,
                'academic_year' => date('Y')
            ]);
    }
}
