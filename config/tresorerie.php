<?php

/**
 * Configuration de l'application Trésorerie
 */

return [
    // Moyens de paiement disponibles
    'payment_methods' => [
        'especes' => 'Espèces',
        'mobile_money' => 'Mobile Money',
        'virement' => 'Virement bancaire',
    ],

    // Types de factures
    'invoice_types' => [
        'eau' => 'Eau',
        'electricite' => 'Électricité',
        'maintenance' => 'Maintenance/Réparation',
        'assurance' => 'Assurance',
        'autre' => 'Autre',
    ],

    // Seuils d'alertes pour le tableau de bord
    'alerts' => [
        'max_student_debt' => 50000, // Montant max d'endettement étudiant pour alerte
        'max_landlord_debt' => 100000, // Montant max dû aux bailleurs pour alerte
        'low_balance_warning' => 10000, // Solde minimum avant alerte
    ],

    // Formats de rapports
    'reports' => [
        'date_format' => 'd/m/Y',
        'currency' => 'XOF', // Monnaie par défaut
        'currency_symbol' => 'CFA',
    ],

    // Pagination
    'pagination' => [
        'per_page' => 20,
    ],

    // Informations amicale
    'organization' => [
        'name' => 'Amicale des Étudiants',
        'email' => 'tresorerie@amicale.local',
        'phone' => '+00 00 00 00 00',
    ],
];
