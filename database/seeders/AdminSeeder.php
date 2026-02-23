<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    User::updateOrCreate(
        ['email' => 'admin@treso.local'],
        [
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]
    );
}
}
