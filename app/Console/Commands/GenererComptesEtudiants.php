<?php
// app/Console/Commands/GenererComptesEtudiants.php

namespace App\Console\Commands;

use App\Models\Etudiant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenererComptesEtudiants extends Command
{
    protected $signature   = 'etudiants:generer-comptes';
    protected $description = 'Génère les comptes users pour les étudiants qui n\'en ont pas';

    public function handle()
    {
        // Récupère tous les étudiants sans user_id
        $etudiants = Etudiant::whereNull('user_id')->whereNotNull('email')->get();

        if ($etudiants->isEmpty()) {
            $this->info('Tous les étudiants ont déjà un compte.');
            return;
        }

        $this->info("Génération des comptes pour {$etudiants->count()} étudiant(s)...");

        $bar = $this->output->createProgressBar($etudiants->count());
        $bar->start();

        foreach ($etudiants as $etudiant) {
            // Vérifie que l'email n'existe pas déjà dans users
            if (User::where('email', $etudiant->email)->exists()) {
                $user = User::where('email', $etudiant->email)->first();
            } else {
                $user = User::create([
                    'name'     => $etudiant->nom,
                    'email'    => $etudiant->email,
                    'password' => Hash::make('123456789'),
                    'role'     => 'client',
                ]);
            }

            $etudiant->update(['user_id' => $user->id]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Comptes générés avec succès ! Mot de passe par défaut : 123456789');
    }
}