<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Trimestriel T{{ $trimestre }} {{ $annee }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px solid #8B5CF6; }
        .header h1 { color: #7C3AED; margin: 0 0 5px 0; font-size: 20px; }
        .section { margin-bottom: 20px; page-break-inside: avoid; }
        .section-title { background-color: #8B5CF6; color: white; padding: 6px 8px; font-weight: bold; margin-bottom: 8px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 10px; }
        table th { background-color: #E5E7EB; padding: 5px; text-align: left; border: 1px solid #D1D5DB; font-weight: bold; }
        table td { padding: 4px 5px; border: 1px solid #D1D5DB; }
        .amount-positive { color: #10B981; font-weight: bold; }
        .amount-negative { color: #EF4444; font-weight: bold; }
        .summary-box { background-color: #F3F4F6; padding: 10px; border-left: 4px solid #8B5CF6; margin-bottom: 10px; }
        .summary-item { display: flex; justify-content: space-between; padding: 4px 0; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #6B7280; border-top: 1px solid #D1D5DB; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RAPPORT TRIMESTRIEL</h1>
        <h2>Coordination Des Étudiant Thiessois a Bambey</h2>
        <p><strong>Période :</strong> {{ $periode }}</p>
        <p><strong>Généré le :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <!-- Synthèse -->
    <div class="section">
        <div class="section-title">SYNTHÈSE TRIMESTRIELLE</div>
        <div class="summary-box">
            <div class="summary-item">
                <span>Total recettes :</span>
                <span class="amount-positive">{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-item">
                <span>Total dépenses :</span>
                <span class="amount-negative">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="summary-item" style="border-top: 2px solid #8B5CF6; padding-top: 8px; margin-top: 8px;">
                <span><strong>Solde du trimestre :</strong></span>
                <span class="{{ $soldeCaisse >= 0 ? 'amount-positive' : 'amount-negative' }}" style="font-size: 13px;">
                    {{ number_format($soldeCaisse, 0, ',', ' ') }} FCFA
                </span>
            </div>
        </div>
    </div>

    <!-- Recettes -->
    <div class="section">
        <div class="section-title">RECETTES ({{ $paiements->count() }} paiements)</div>
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
                    <tr style="background-color: #F3F4F6;">
                        <td colspan="4" style="text-align: right;"><strong>TOTAL :</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="padding: 8px; background-color: #FEF3C7;">Aucun paiement.</p>
        @endif
    </div>

    <!-- Dépenses -->
    <div class="section">
        <div class="section-title">DÉPENSES ({{ $factures->count() }} factures)</div>
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
                    <tr style="background-color: #F3F4F6;">
                        <td colspan="4" style="text-align: right;"><strong>TOTAL :</strong></td>
                        <td style="text-align: right;"><strong>{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="padding: 8px; background-color: #FEF3C7;">Aucune facture.</p>
        @endif
    </div>

    <!-- Statistiques étudiants -->
    <div class="section">
        <div class="section-title">STATISTIQUES ÉTUDIANTS</div>
        <table style="width: 70%;">
            <tr>
                <td><strong>Nombre total d'étudiants :</strong></td>
                <td style="text-align: right;">{{ $nombreEtudiants }}</td>
            </tr>
            <tr style="background-color: #FEE2E2;">
                <td><strong>Étudiants débiteurs :</strong></td>
                <td style="text-align: right;" class="amount-negative">
                    {{ $nombreDebiteurs }} ({{ number_format($totalDettes, 0, ',', ' ') }} FCFA)
                </td>
            </tr>
            <tr style="background-color: #D1FAE5;">
                <td><strong>Étudiants créditeurs :</strong></td>
                <td style="text-align: right;" class="amount-positive">
                    {{ $nombreCrediteurs }} ({{ number_format($totalAvances, 0, ',', ' ') }} FCFA)
                </td>
            </tr>
            <tr style="background-color: #E0E7FF;">
                <td><strong>Étudiants à jour :</strong></td>
                <td style="text-align: right;">
                    {{ $nombreEtudiants - $nombreDebiteurs - $nombreCrediteurs }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Situation par maison -->
    <div class="section">
        <div class="section-title">SITUATION PAR MAISON</div>
        <table>
            <thead>
                <tr>
                    <th>Maison</th>
                    <th style="text-align: center;">Étudiants</th>
                    <th style="text-align: right;">Solde</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maisons as $maison)
                <tr>
                    <td>{{ $maison->nom }}</td>
                    <td style="text-align: center;">{{ $maison->etudiants->count() }}</td>
                    <td style="text-align: right;" class="{{ $maison->solde >= 0 ? 'amount-positive' : 'amount-negative' }}">
                        {{ number_format($maison->solde, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong> © {{ date('Y') }} C.E.</strong></p>
        <p>Document généré automatiquement par le système de gestion - Document confidentiel</p>
    </div>
</body>
</html>
