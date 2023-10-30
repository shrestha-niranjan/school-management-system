<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()
            ->updateOrCreate([
                'email' => 'super@admin.com'
            ], [
                'password' => 'password',
                'name' => 'Admin'
            ]);
    }
}
