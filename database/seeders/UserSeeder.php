<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $data = [
            ['username' => 'superadmin', 'name' => 'Super Admin'],
            ['username' => 'admin', 'name' => 'Admin'],
            ['username' => 'user', 'name' => 'User'],
        ];

        foreach ($data as $item) {
            User::updateOrCreate(
                ['username' => $item['username']],
                [
                    'name'     => $item['name'],
                    'username' => $item['username'],
                    'email'    => $item['username'] . '@email.com',
                    'password' => bcrypt('password'),
                ]
            );
        }
    }
}
