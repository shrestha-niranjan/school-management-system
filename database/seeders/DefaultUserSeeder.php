<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefaultUserSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'Teacher']);
        Role::firstOrCreate(['name' => 'Student']);

        Role::firstOrCreate(['name' => 'Super Admin'])
          ->givePermissionTo(Permission::pluck('name'));

        foreach ($this->users() as $email => $userInfo) {
            $user = User::firstOrCreate(
                [
                    'email' => $email,
                    'grade_id' => 10
                ],
                [
                    'password' => $userInfo['password'],
                    'name' => $userInfo['name']
                ]
            );

            if (
                isset($userInfo['role']) &&
                !empty($userInfo['role'])
            ) {
                $role = Role::query()
                    ->firstOrCreate([
                        'name' => $userInfo['role']
                    ])
                    ->givePermissionTo(
                        (array) $this->defaultMemberPermissions()
                    );

                $user->assignRole($role);
            }
        }
    }

    private function users()
    {
        return [
            'super@admin.com' => [
                'name' => 'Super Admin',
                'password' => 'password',
                'role' => 'Super Admin'
            ],
            'admin@gmail.com' => [
                'name' => 'Admin User',
                'password' => 'password',
                'role' => 'Super Admin'
            ]
        ];
    }

    private function defaultMemberPermissions(): array
    {
        return
          [
            'login',
            'register',
            'request password',
            'email password',
            'reset password',
            'store password',
            'confirm password',
            'update password',
            'notice verification',
            'verify verification',
            'send verification',
            'logout',
            'dashboard'
          ];
        ;
    }
}
