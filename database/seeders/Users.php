<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        User::create([
            'npk' => '123456789',
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'idRole' => 1,
            'noHp' => "089654825055",

        ]);
        User::create([
            'npk' => '1234567891',
            'name' => 'Admin',
            'email' => 'admin1@gmail.com',
            'password' => bcrypt('admin'),
            'idRole' => 2,
            'noHp' => "089654825055",

        ]);
        User::create([
            'npk' => '1234567897',
            'name' => 'Admin',
            'email' => 'admin2@gmail.com',
            'password' => bcrypt('admin'),
            'idRole' => 3,
            'noHp' => "089654825055",

        ]);
        User::create([
            'npk' => '1234567898',
            'name' => 'Admin',
            'email' => 'admin3@gmail.com',
            'password' => bcrypt('admin'),
            'idRole' => 4,
            'noHp' => "089654825055",

        ]);
        User::create([
            'npk' => '111223344',
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('user'),
            'idRole' => 5,
            'noHp' => "089654825056",

        ]);
    }
}