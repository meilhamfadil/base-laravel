<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([[
            'id' => 1,
            'name' => 'Superadmin',
            'slug' => 'Superadmin',
            'description' => 'Mendapatkan akses ke seluruh sistem'
        ], [
            'id' => 2,
            'name' => 'Admin',
            'slug' => 'Admin',
            'description' => 'Mendapatkan akses ke seluruh fitur'
        ]]);
        DB::table('user')->insert([[
            'name' => 'Superadmin',
            'email' => 'superadmin@mail.com',
            'password' => Hash::make('password'),
            'role_id' => 1
        ], [
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role_id' => 2
        ]]);
        DB::table('menu')->insert([
            [
                'id' => 1,
                'parent' => 0,
                'name' => 'Main Menu',
                'type' => 'label',
                'icon' => null,
                'link' => null,
                'order' => 1,
                'manageable' => false
            ],
            [
                'id' => 2,
                'parent' => 1,
                'name' => 'Dashboard',
                'type' => 'menu',
                'icon' => 'tachometer-alt',
                'link' => '/dashboard',
                'order' => 1,
                'manageable' => false
            ]
        ]);
    }
}
