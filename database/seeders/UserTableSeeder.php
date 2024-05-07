<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'a@a.com',
                'password' => bcrypt('123456'),
            ],
            [
                'email' => 'b@b.com',
                'password' => bcrypt('123456'),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
