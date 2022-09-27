<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'administrator',
                'phone_number' => '081213405817',
                'status' => 'available',
            ],
            [
                'name' => 'User',
                'email' => 'kasir1@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'user',
                'phone_number' => '081213405811',
                'status' => 'available',
            ],
            [
                'name' => 'Driver',
                'email' => 'driver@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'driver',
                'phone_number' => '081213405812',
                'status' => 'available',
            ],
            [
                'name' => 'Teknisi',
                'email' => 'teknisi@gmail.com',
                'password' => bcrypt('123'),
                'role' => 'teknisi',
                'phone_number' => '081213405813',
                'status' => 'available',
            ],
            
        );

        array_map(function (array $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }, $users);
    }
}
