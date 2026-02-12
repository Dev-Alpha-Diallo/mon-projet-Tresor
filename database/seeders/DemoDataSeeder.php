<?php

namespace Database\Seeders;

// use App\Models\Bailleur;
// use App\Models\Maison;
// use App\Models\Etudiant;
// use App\Models\Paiement;
// use App\Models\Facture;
// use App\Models\PaiementBailleur;
// use Illuminate\Database\Seeder;
// use Carbon\Carbon;

// class DemoDataSeeder extends Seeder
// {
//     /**
//      * Seed de données de démonstration
//      */
//     public function run(): void
//     {
//         // Créer des bailleurs
//         $bailleur1 = Bailleur::create([
//             'nom' => 'Monsieur Diop',
//             'telephone' => '+221 77 123 45 67',
//         ]);

//         $bailleur2 = Bailleur::create([
//             'nom' => 'Madame Ndiaye',
//             'telephone' => '+221 76 987 65 43',
//         ]);

//         // Créer des maisons
//         $maison1 = Maison::create([
//             'nom' => 'Villa des Palmiers',
//             'adresse' => 'Sicap Liberté, Dakar',
//             'bailleur_id' => $bailleur1->id,
//             'loyer_total_mensuel' => 500000,
//         ]);

//         $maison2 = Maison::create([
//             'nom' => 'Résidence Sahel',
//             'adresse' => 'Mermoz, Dakar',
//             'bailleur_id' => $bailleur1->id,
//             'loyer_total_mensuel' => 400000,
//         ]);

//         $maison3 = Maison::create([
//             'nom' => 'Appartement Almadies',
//             'adresse' => 'Les Almadies, Dakar',
//             'bailleur_id' => $bailleur2->id,
//             'loyer_total_mensuel' => 600000,
//         ]);

//         // Créer des étudiants
//         $etudiants = [
//             [
//                 'nom' => 'Amadou Ba',
//                 'filiere' => 'Informatique',
//                 'maison_id' => $maison1->id,
//                 'chambre' => 'A1',
//                 'loyer_mensuel' => 100000,
//             ],
//             [
//                 'nom' => 'Fatou Sall',
//                 'filiere' => 'Gestion',
//                 'maison_id' => $maison1->id,
//                 'chambre' => 'A2',
//                 'loyer_mensuel' => 100000,
//             ],
//             [
//                 'nom' => 'Moussa Diallo',
//                 'filiere' => 'Droit',
//                 'maison_id' => $maison1->id,
//                 'chambre' => 'A3',
//                 'loyer_mensuel' => 100000,
//             ],
//             [
//                 'nom' => 'Aissatou Kane',
//                 'filiere' => 'Médecine',
//                 'maison_id' => $maison2->id,
//                 'chambre' => 'B1',
//                 'loyer_mensuel' => 120000,
//             ],
//             [
//                 'nom' => 'Ousmane Fall',
//                 'filiere' => 'Économie',
//                 'maison_id' => $maison2->id,
//                 'chambre' => 'B2',
//                 'loyer_mensuel' => 120000,
//             ],
//             [
//                 'nom' => 'Mariama Sy',
//                 'filiere' => 'Lettres',
//                 'maison_id' => $maison3->id,
//                 'chambre' => 'C1',
//                 'loyer_mensuel' => 150000,
//             ],
//             [
//                 'nom' => 'Ibrahima Sarr',
//                 'filiere' => 'Ingénierie',
//                 'maison_id' => $maison3->id,
//                 'chambre' => 'C2',
//                 'loyer_mensuel' => 150000,
//             ],
//         ];

//         foreach ($etudiants as $etudiantData) {
//             Etudiant::create($etudiantData);
//         }

//         // Créer des paiements (mois en cours et précédents)
//         $etudiants = Etudiant::all();
        
//         foreach ($etudiants as $etudiant) {
//             // Paiements du mois dernier
//             Paiement::create([
//                 'etudiant_id' => $etudiant->id,
//                 'montant' => $etudiant->loyer_mensuel,
//                 'date_paiement' => Carbon::now()->subMonth()->startOfMonth()->addDays(5),
//                 'moyen_paiement' => ['especes', 'mobile_money', 'virement'][rand(0, 2)],
//                 'remarque' => 'Paiement du mois dernier',
//             ]);

//             // Paiements du mois en cours (certains ont payé, d'autres pas encore)
//             if (rand(0, 1)) {
//                 Paiement::create([
//                     'etudiant_id' => $etudiant->id,
//                     'montant' => $etudiant->loyer_mensuel,
//                     'date_paiement' => Carbon::now()->startOfMonth()->addDays(rand(1, 15)),
//                     'moyen_paiement' => ['especes', 'mobile_money', 'virement'][rand(0, 2)],
//                     'remarque' => 'Paiement du mois en cours',
//                 ]);
//             }
//         }

//         // Créer des factures
//         Facture::create([
//             'numero_facture' => 'SENELEC-2024-001',
//             'type' => 'electricite',
//             'maison_id' => $maison1->id,
//             'montant' => 45000,
//             'date_paiement' => Carbon::now()->subMonth()->addDays(10),
//             'description' => 'Facture électricité mois dernier',
//         ]);

//         Facture::create([
//             'numero_facture' => 'SDE-2024-001',
//             'type' => 'eau',
//             'maison_id' => $maison1->id,
//             'montant' => 25000,
//             'date_paiement' => Carbon::now()->subMonth()->addDays(12),
//             'description' => 'Facture eau mois dernier',
//         ]);

//         Facture::create([
//             'numero_facture' => 'REP-2024-001',
//             'type' => 'reparation',
//             'maison_id' => $maison2->id,
//             'montant' => 80000,
//             'date_paiement' => Carbon::now()->subMonth()->addDays(15),
//             'description' => 'Réparation plomberie',
//         ]);

//         Facture::create([
//             'numero_facture' => 'SENELEC-2024-002',
//             'type' => 'electricite',
//             'maison_id' => $maison2->id,
//             'montant' => 38000,
//             'date_paiement' => Carbon::now()->addDays(5),
//             'description' => 'Facture électricité mois en cours',
//         ]);

//         Facture::create([
//             'numero_facture' => 'SENELEC-2024-003',
//             'type' => 'electricite',
//             'maison_id' => $maison3->id,
//             'montant' => 52000,
//             'date_paiement' => Carbon::now()->addDays(7),
//             'description' => 'Facture électricité mois en cours',
//         ]);

//         // Créer des paiements aux bailleurs
//         PaiementBailleur::create([
//             'bailleur_id' => $bailleur1->id,
//             'maison_id' => $maison1->id,
//             'montant' => 500000,
//             'date_paiement' => Carbon::now()->subMonth()->addDays(25),
//             'remarque' => 'Paiement loyer mois dernier',
//         ]);

//         PaiementBailleur::create([
//             'bailleur_id' => $bailleur1->id,
//             'maison_id' => $maison2->id,
//             'montant' => 400000,
//             'date_paiement' => Carbon::now()->subMonth()->addDays(25),
//             'remarque' => 'Paiement loyer mois dernier',
//         ]);

//         $this->command->info('✅ Données de démonstration créées avec succès !');
//         $this->command->info('   - 2 Bailleurs');
//         $this->command->info('   - 3 Maisons');
//         $this->command->info('   - 7 Étudiants');
//         $this->command->info('   - Paiements et factures');
//     }
