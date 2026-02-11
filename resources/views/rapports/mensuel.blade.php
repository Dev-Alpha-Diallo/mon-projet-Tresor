<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Trésorerie {{ $moisNom }} {{ $annee }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #60A5FA;
        }
        .header h1 {
            color: #3B82F6;
            margin: 0 0 10px 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #60A5FA;
            color: white;
            padding: 8px 10px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #E5E7EB;
            padding: 8px;
            text-align: left;
            border: 1px solid #D1D5DB;
            font-weight: bold;
        }
        table td {
            padding: 6px 8px;
            border: 1px solid #D1D5DB;
        }
        .amount-positive {
            color: #10B981;
            font-weight: bold;
        }
        .amount-negative {
            color: #EF4444;
            font-weight: bold;
        }
        .summary-box {
            background-color: #F3F4F6;
            padding: 15px;
            border-left: 4px solid #60A5FA;
            margin-bottom: 15px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
            border-top: 1px solid #D1D5DB;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>RAPPORT DE TRÉSORERIE</h1>
        <h2>COORDINATION DES ÉTUDIANTS DE THIES </h2>
        <p><strong>Période :</strong> {{ $moisNom }} {{ $annee }}</p>
        <p><strong>Généré le :</strong> {{ $dateGeneration }}</p>
    </div>

    <!-- Synthèse financière -->
    <div class="section">
        <div class="section-title">SYNTHÈSE FINANCIÈRE</div>
        <div class="summary-box">
            <div class="summary-item">
                <span>Solde début de mois :</span>
                <span class="{{ $soldeDebut >= 0 ? 'amount-positive' : 'amount-negative' }}">
                    {{ number_format($soldeDebut, 0, ',', ' ') }} FCFA
                </span>
            </div>
            <div class="summary-item">
                <span>Total recettes :</span>
                <span class="amount-positive">{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-item">
                <span>Total dépenses :</span>
                <span class="amount-negative">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-item" style="border-top: 2px solid #60A5FA; padding-top: 10px; margin-top: 10px;">
                <span><strong>Solde fin de mois :</strong></span>
                <span class="{{ $soldeFin >= 0 ? 'amount-positive' : 'amount-negative' }}" style="font-size: 14px;">
                    {{ number_format($soldeFin, 0, ',', ' ') }} FCFA
                </span>
            </div>
            <div class="summary-item">
                <span>Excédent / Déficit du mois :</span>
                <span class="{{ $excedentDeficit >= 0 ? 'amount-positive' : 'amount-negative' }}">
                    {{ number_format($excedentDeficit, 0, ',', ' ') }} FCFA
                </span>
            </div>
        </div>
    </div>

    <!-- Recettes -->
    <div class="section">
        <div class="section-title">RECETTES (Paiements étudiants)</div>
        @if($paiementsMois->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Étudiant</th>
                        <th>Maison</th>
                        <th>Moyen</th>
                        <th style="text-align: right;">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paiementsMois as $paiement)
                    <tr>
                        <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                        <td>{{ $paiement->etudiant->nom }}</td>
                        <td>{{ $paiement->etudiant->maison->nom }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $paiement->moyen_paiement)) }}</td>
                        <td style="text-align: right;">{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                    </tr>
                    @endforeach
                    <tr style="background-color: #F3F4F6;">
                        <td colspan="4" style="text-align: right;"><strong>TOTAL :</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="padding: 10px; background-color: #FEF3C7; border: 1px solid #FCD34D;">
                Aucun paiement enregistré ce mois.
            </p>
        @endif
    </div>

    <!-- Dépenses -->
    <div class="section">
        <div class="section-title">DÉPENSES (Factures)</div>
        
        <!-- Dépenses par type -->
        @if($depensesParType->count() > 0)
            <div style="margin-bottom: 15px;">
                <strong>Répartition par type :</strong>
                <table style="width: 50%; margin-top: 5px;">
                    @foreach($depensesParType as $type => $montant)
                    <tr>
                        <td>{{ ucfirst($type) }}</td>
                        <td style="text-align: right;">{{ number_format($montant, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        @endif

        @if($facturesMois->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>N° Facture</th>
                        <th>Type</th>
                        <th>Maison</th>
                        <th style="text-align: right;">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facturesMois as $facture)
                    <tr>
                        <td>{{ $facture->date_paiement->format('d/m/Y') }}</td>
                        <td>{{ $facture->numero_facture }}</td>
                        <td>{{ ucfirst($facture->type) }}</td>
                        <td>{{ $facture->maison->nom }}</td>
                        <td style="text-align: right;">{{ number_format($facture->montant, 0, ',', ' ') }}</td>
                    </tr>
                    @endforeach
                    <tr style="background-color: #F3F4F6;">
                        <td colspan="4" style="text-align: right;"><strong>TOTAL :</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="padding: 10px; background-color: #FEF3C7; border: 1px solid #FCD34D;">
                Aucune dépense enregistrée ce mois.
            </p>
        @endif
    </div>

    <!-- Étudiants débiteurs -->
    <div class="section">
        <div class="section-title">ÉTUDIANTS DÉBITEURS ({{ $etudiantsDebiteurs->count() }})</div>
        @if($etudiantsDebiteurs->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Maison</th>
                        <th>Loyer mensuel</th>
                        <th style="text-align: right;">Dette</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($etudiantsDebiteurs as $etudiant)
                    <tr>
                        <td>{{ $etudiant->nom }}</td>
                        <td>{{ $etudiant->maison->nom }}</td>
                        <td>{{ number_format($etudiant->loyer_mensuel, 0, ',', ' ') }}</td>
                        <td style="text-align: right;" class="amount-negative">
                            {{ number_format(abs($etudiant->solde), 0, ',', ' ') }}
                        </td>
                    </tr>
                    @endforeach
                    <tr style="background-color: #FEE2E2;">
                        <td colspan="3" style="text-align: right;"><strong>TOTAL DETTES :</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format(abs($etudiantsDebiteurs->sum('solde')), 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="padding: 10px; background-color: #D1FAE5; border: 1px solid #6EE7B7;">
                Aucun étudiant débiteur.
            </p>
        @endif
    </div>

    <!-- Étudiants créditeurs -->
    <div class="section">
        <div class="section-title">ÉTUDIANTS CRÉDITEURS ({{ $etudiantsCrediteurs->count() }})</div>
        @if($etudiantsCrediteurs->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Maison</th>
                        <th>Loyer mensuel</th>
                        <th style="text-align: right;">Avance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($etudiantsCrediteurs as $etudiant)
                    <tr>
                        <td>{{ $etudiant->nom }}</td>
                        <td>{{ $etudiant->maison->nom }}</td>
                        <td>{{ number_format($etudiant->loyer_mensuel, 0, ',', ' ') }}</td>
                        <td style="text-align: right;" class="amount-positive">
                            {{ number_format($etudiant->solde, 0, ',', ' ') }}
                        </td>
                    </tr>
                    @endforeach
                    <tr style="background-color: #D1FAE5;">
                        <td colspan="3" style="text-align: right;"><strong>TOTAL AVANCES :</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($etudiantsCrediteurs->sum('solde'), 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="padding: 10px; background-color: #FEF3C7; border: 1px solid #FCD34D;">
                Aucun étudiant créditeur.
            </p>
        @endif
    </div>

    <!-- Situation par maison -->
    <div class="section">
        <div class="section-title">SITUATION FINANCIÈRE PAR MAISON</div>
        <table>
            <thead>
                <tr>
                    <th>Maison</th>
                    <th>Bailleur</th>
                    <th>Étudiants</th>
                    <th style="text-align: right;">Recettes</th>
                    <th style="text-align: right;">Dépenses</th>
                    <th style="text-align: right;">Solde</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maisons as $maison)
                <tr>
                    <td>{{ $maison->nom }}</td>
                    <td>{{ $maison->bailleur->nom }}</td>
                    <td>{{ $maison->etudiants->count() }}</td>
                    <td style="text-align: right;">{{ number_format($maison->total_recettes, 0, ',', ' ') }}</td>
                    <td style="text-align: right;">{{ number_format($maison->total_depenses, 0, ',', ' ') }}</td>
                    <td style="text-align: right;" class="{{ $maison->solde >= 0 ? 'amount-positive' : 'amount-negative' }}">
                        {{ number_format($maison->solde, 0, ',', ' ') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Document généré automatiquement par le système de gestion de trésorerie - C.E.T</p>
        <p>{{ $dateGeneration }}</p>
    </div>
</body>
</html>