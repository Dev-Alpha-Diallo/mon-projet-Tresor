<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Annuel {{ $annee }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px solid #059669; }
        .header h1 { color: #047857; margin: 0 0 5px 0; font-size: 22px; }
        .section { margin-bottom: 20px; page-break-inside: avoid; }
        .section-title { background-color: #059669; color: white; padding: 6px 8px; font-weight: bold; margin-bottom: 8px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10px; }
        table th { background-color: #E5E7EB; padding: 5px; text-align: left; border: 1px solid #D1D5DB; font-weight: bold; }
        table td { padding: 4px 5px; border: 1px solid #D1D5DB; }
        .amount-positive { color: #10B981; font-weight: bold; }
        .amount-negative { color: #EF4444; font-weight: bold; }
        .summary-box { background-color: #F0FDF4; padding: 10px; border-left: 4px solid #059669; margin-bottom: 10px; }
        .summary-item { display: flex; justify-content: space-between; padding: 4px 0; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #6B7280; border-top: 1px solid #D1D5DB; padding-top: 8px; }
        .chart-row { background-color: #F9FAFB; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RAPPORT ANNUEL - MANDAT {{ $annee }}</h1>
        <h2> Coordination Des Étudiant Thiessois a Bambey</h2>
        <p><strong>Période :</strong> 01/01/{{ $annee }} - 31/12/{{ $annee }}</p>
        <p><strong>Généré le :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- Synthèse globale -->
    <div class="section">
        <div class="section-title">BILAN ANNUEL</div>
        <div class="summary-box">
            <div class="summary-item">
                <span><strong>Total recettes de l'année :</strong></span>
                <span class="amount-positive" style="font-size: 14px;">{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-item">
                <span><strong>Total dépenses de l'année :</strong></span>
                <span class="amount-negative" style="font-size: 14px;">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-item" style="border-top: 2px solid #059669; padding-top: 8px; margin-top: 8px;">
                <span><strong>RÉSULTAT NET :</strong></span>
                <span class="{{ $soldeCaisse >= 0 ? 'amount-positive' : 'amount-negative' }}" style="font-size: 16px;">
                    {{ number_format($soldeCaisse, 0, ',', ' ') }} FCFA
                </span>
            </div>
            <div class="summary-item">
                <span>Nombre de paiements :</span>
                <span>{{ $paiements->count() }}</span>
            </div>
            <div class="summary-item">
                <span>Nombre de factures :</span>
                <span>{{ $factures->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Évolution mensuelle -->
    <div class="section">
        <div class="section-title">ÉVOLUTION MENSUELLE</div>
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th style="text-align: right;">Recettes</th>
                    <th style="text-align: right;">Dépenses</th>
                    <th style="text-align: right;">Solde</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statistiquesMensuelles as $stat)
                <tr class="chart-row">
                    <td>{{ $stat['nomMois'] }}</td>
                    <td style="text-align: right;" class="amount-positive">{{ number_format($stat['recettes'], 0, ',', ' ') }}</td>
                    <td style="text-align: right;" class="amount-negative">{{ number_format($stat['depenses'], 0, ',', ' ') }}</td>
                    <td style="text-align: right;" class="{{ $stat['solde'] >= 0 ? 'amount-positive' : 'amount-negative' }}">
                        {{ number_format($stat['solde'], 0, ',', ' ') }}
                    </td>
                </tr>
                @endforeach
                <tr style="background-color: #D1FAE5; font-weight: bold;">
                    <td><strong>TOTAL ANNUEL</strong></td>
                    <td style="text-align: right;"><strong>{{ number_format($totalRecettes, 0, ',', ' ') }}</strong></td>
                    <td style="text-align: right;"><strong>{{ number_format($totalDepenses, 0, ',', ' ') }}</strong></td>
                    <td style="text-align: right;"><strong>{{ number_format($soldeCaisse, 0, ',', ' ') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Recettes -->
    <div class="section">
        <div class="section-title">RECETTES DE L'ANNÉE ({{ $paiements->count() }} paiements)</div>
        @if($paiements->count() > 0)
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
                    @foreach($paiements as $paiement)
                    <tr>
                        <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                        <td>{{ $paiement->etudiant->nom }}</td>
                        <td>{{ $paiement->etudiant->maison->nom }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $paiement->moyen_paiement)) }}</td>
                        <td style="text-align: right;">{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                    </tr>
                    @endforeach
                    <tr style="background-color: #D1FAE5;">
                        <td colspan="4" style="text-align: right;"><strong>TOTAL RECETTES :</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="padding: 8px; background-color: #FEF3C7;">Aucun paiement enregistré.</p>
        @endif
    </div>

    <!-- Dépenses -->
    <div class="section">
        <div class="section-title">DÉPENSES DE L'ANNÉE ({{ $factures->count() }} factures)</div>
        @if($factures->count() > 0)
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
                    @foreach($factures as $facture)
                    <tr>
                        <td>{{ $facture->date_paiement->format('d/m/Y') }}</td>
                        <td>{{ $facture->numero_facture }}</td>
                        <td>{{ ucfirst($facture->type) }}</td>
                        <td>{{ $facture->maison->nom }}</td>
                        <td style="text-align: right;">{{ number_format($facture->montant, 0, ',', ' ') }}</td>
                    </tr>
                    @endforeach
                    <tr style="background-color: #FEE2E2;">
                        <td colspan="4" style="text-align: right;"><strong>TOTAL DÉPENSES :</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="padding: 8px; background-color: #FEF3C7;">Aucune facture enregistrée.</p>
        @endif
    </div>

    <!-- Statistiques étudiants -->
    <div class="section">
        <div class="section-title">STATISTIQUES ÉTUDIANTS</div>
        <table style="width: 60%;">
            <tr>
                <td><strong>Nombre total d'étudiants :</strong></td>
                <td style="text-align: right;">{{ $nombreEtudiants }}</td>
            </tr>
            <tr class="chart-row">
                <td>Étudiants débiteurs :</td>
                <td style="text-align: right;" class="amount-negative">{{ $nombreDebiteurs }} ({{ number_format($totalDettes, 0, ',', ' ') }} FCFA)</td>
            </tr>
            <tr>
                <td>Étudiants créditeurs :</td>
                <td style="text-align: right;" class="amount-positive">{{ $nombreCrediteurs }} ({{ number_format($totalAvances, 0, ',', ' ') }} FCFA)</td>
            </tr>
            <tr class="chart-row">
                <td>Étudiants à jour :</td>
                <td style="text-align: right;">{{ $nombreEtudiants - $nombreDebiteurs - $nombreCrediteurs }}</td>
            </tr>
            <tr>
                <td><strong>Taux de recouvrement :</strong></td>
                <td style="text-align: right;">
                    {{ number_format(($totalRecettes / max(($totalRecettes + $totalDettes), 1)) * 100, 1) }}%
                </td>
            </tr>
        </table>
    </div>

    <!-- Situation par maison -->
    <div class="section">
        <div class="section-title">BILAN PAR MAISON</div>
        <table>
            <thead>
                <tr>
                    <th>Maison</th>
                    <th>Bailleur</th>
                    <th style="text-align: center;">Étudiants</th>
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
                    <td style="text-align: center;">{{ $maison->etudiants->count() }}</td>
                    <td style="text-align: right;" class="amount-positive">{{ number_format($maison->total_recettes, 0, ',', ' ') }}</td>
                    <td style="text-align: right;" class="amount-negative">{{ number_format($maison->total_depenses, 0, ',', ' ') }}</td>
                    <td style="text-align: right;" class="{{ $maison->solde >= 0 ? 'amount-positive' : 'amount-negative' }}">
                        {{ number_format($maison->solde, 0, ',', ' ') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Recommandations -->
    <div class="section">
        <div class="section-title">OBSERVATIONS ET RECOMMANDATIONS</div>
        <div style="padding: 10px; background-color: #F9FAFB;">
            @if($soldeCaisse > 0)
                <p>✅ <strong>Excédent positif</strong> : L'année {{ $annee }} se clôture avec un excédent de {{ number_format($soldeCaisse, 0, ',', ' ') }} FCFA.</p>
            @else
                <p>⚠️ <strong>Déficit</strong> : L'année {{ $annee }} se clôture avec un déficit de {{ number_format(abs($soldeCaisse), 0, ',', ' ') }} FCFA.</p>
            @endif
            
            @if($nombreDebiteurs > 0)
                <p>📊 {{ $nombreDebiteurs }} étudiant(s) sont en situation de dette pour un total de {{ number_format($totalDettes, 0, ',', ' ') }} FCFA.</p>
            @endif
            
            <p style="margin-top: 10px;"><strong>Taux de recouvrement :</strong> {{ number_format(($totalRecettes / max(($totalRecettes + $totalDettes), 1)) * 100, 1) }}%</p>
        </div>
    </div>

    <div class="footer">
        <p><strong>© {{ date('Y') }} C.E.T</strong></p>
        <p>Rapport de mandat généré automatiquement - Document confidentiel</p>
    </div>
</body>
</html>