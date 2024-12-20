<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Adviser user',
                'email'=> 'adviser@gmail.com',
                'password'=> 'adviser1234',
                'role'=> 'ADVISER'
            ],
            [
                'name' => 'POD user',
                'email'=> 'pod@gmail.com',
                'password'=> 'pod1234',
                'role'=> 'POD'
            ],
            [
                'name' => 'Admin user',
                'email'=> 'admin@gmail.com',
                'password'=> 'admin1234',
                'role'=> 'ADMIN'
            ],
            [
                'name' => 'SuperAdmin user',
                'email'=> 'superadmin@gmail.com',
                'password'=> 'superadmin1234',
                'role'=> 'SUPER-ADMIN'
            ]

        ];

        foreach ($users as $user) {
            $created_user = User::create([
                'name' => $user['name'],
                'email'=> $user['email'],
                'password'=> Hash::make($user['password']),
            ]);
            
            $created_user->assignRole($user['role']);
        }
    }
}
