<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefaultUserSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->users() as $email => $userInfo) {
            User::firstOrCreate(
                [
                    'email' => $email,
                ],
                [
                    'password' => Hash::make($userInfo['password']),
                    'name' => $userInfo['name']
                ]
            );
        }
    }

    private function users()
    {
        return [
            'super@admin.com' => [
                'name' => 'Super Admin',
                'password' => 'password',
            ]
        ];
    }
}
