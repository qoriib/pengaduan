<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'MPS User',
                'email' => 'mps@mail.com',
                'role' => 'MPS',
                'password' => Hash::make('password'), // Default password
            ],
            [
                'name' => 'QQ User',
                'email' => 'qq@mail.com',
                'role' => 'QQ',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'SSGA User',
                'email' => 'ssga@mail.com',
                'role' => 'SSGA',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'LM User',
                'email' => 'lm@mail.com',
                'role' => 'LM',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Distr User',
                'email' => 'distr@mail.com',
                'role' => 'Distr',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'CR User',
                'email' => 'cr@mail.com',
                'role' => 'CR',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'HSSE User',
                'email' => 'hsse@mail.com',
                'role' => 'HSSE',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'ITM User',
                'email' => 'itm@mail.com',
                'role' => 'ITM',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
