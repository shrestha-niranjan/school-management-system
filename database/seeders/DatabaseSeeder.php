<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\RefreshPermissionsCommand;
use App\Models\SchoolSetting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call(RefreshPermissionsCommand::class);

        $this
            ->call([
                GradeSeeder::class,
                SchoolSettingSeeder::class,
                DefaultUserSeeder::class,
                CourseSeeder::class
            ]);
    }
}
