<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'NameRole' => 'Admin'
        ]);
        Role::create([
            'NameRole' => 'Sect. Head'
        ]);
        Role::create([
            'NameRole' => 'Dept. Head'
        ]);
        Role::create([
            'NameRole' => 'PJ Stock'
        ]);
        Role::create([
            'NameRole' => 'Users'
        ]);

    }
}