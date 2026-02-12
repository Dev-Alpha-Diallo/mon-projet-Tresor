<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer l'utilisateur administrateur
        User::firstOrCreate(
            ['email' => 'admin@treso.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Optionnel : Appeler les autres seeders
        $this->call([
            DemoDataSeeder::class,
        ]);
    }
}

