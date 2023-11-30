<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

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
                'address' => 'Manthali, Ramechhap',
                'grade_id' => 10,
                'established_at' => 2038,
                'academic_year' => date('Y') + 56
            ]);
    }
}
