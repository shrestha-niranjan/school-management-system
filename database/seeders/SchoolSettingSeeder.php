<?php

namespace Database\Seeders;

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
                'academic_year' => 2080,
                'established_at' => 2038
            ]);
    }
}
