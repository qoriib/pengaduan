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
                'email' => 'mps@ptpp.com',
                'role' => 'MPS',
                'password' => Hash::make('Ptpp#1234'), // Default password
            ],
            [
                'name' => 'QQ User',
                'email' => 'qq@ptpp.com',
                'role' => 'QQ',
                'password' => Hash::make('Ptpp#1234'),
            ],
            [
                'name' => 'SSGA User',
                'email' => 'ssga@ptpp.com',
                'role' => 'SSGA',
                'password' => Hash::make('Ptpp#1234'),
            ],
            [
                'name' => 'LM User',
                'email' => 'lm@ptpp.com',
                'role' => 'LM',
                'password' => Hash::make('Ptpp#1234'),
            ],
            [
                'name' => 'Distr User',
                'email' => 'distr@ptpp.com',
                'role' => 'Distr',
                'password' => Hash::make('Ptpp#1234'),
            ],
            [
                'name' => 'CR User',
                'email' => 'cr@ptpp.com',
                'role' => 'CR',
                'password' => Hash::make('Ptpp#1234'),
            ],
            [
                'name' => 'HSSE User',
                'email' => 'hsse@ptpp.com',
                'role' => 'HSSE',
                'password' => Hash::make('Ptpp#1234'),
            ],
            [
                'name' => 'ITM User',
                'email' => 'itm@ptpp.com',
                'role' => 'ITM',
                'password' => Hash::make('Ptpp#1234'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
