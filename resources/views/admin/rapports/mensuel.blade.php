<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Mensuel {{ $moisNom }} {{ $annee }}</title>
    <style>
        * { margin: 0; padding: 0; }
        body {
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            font-size: 11px;
            color: #1F2937;
            line-height: 1.5;
        }
        .page {
            page-break-after: always;
            padding: 20px;
        }
        
        /* En-tête professionnelle */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #3B82F6;
        }
        .header h1 {
            color: #1E3A8A;
            margin-bottom: 5px;
            font-size: 24px;
        }
        .header h2 {
            color: #3B82F6;
            font-size: 14px;
            font-weight: normal;
            margin-bottom: 10px;
        }
        .header-info {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #6B7280;
            margin-top: 10px;
        }
        
        /* Sections */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background: linear-gradient(to right, #3B82F6, #1E40AF);
            color: white;
            padding: 10px 12px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        
        /* Synthèse */
        .summary-box {
            background-color: #EFF6FF;
            border-left: 4px solid #3B82F6;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 3px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #DBEAFE;
        }
        .summary-row:last-child {
            border-bottom: none;
        }
        .summary-row.total {
            border-top: 2px solid #3B82F6;
            margin-top: 8px;
            padding-top: 8px;
            font-weight: bold;
            font-size: 12px;
        }
        
        /* Tableaux */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        table th {
            background-color: #F3F4F6;
            padding: 7px;
            text-align: left;
            border: 1px solid #D1D5DB;
            font-weight: 600;
            color: #374151;
        }
        table td {
            padding: 6px 7px;
            border: 1px solid #E5E7EB;
        }
        table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        table tr.total-row {
            background-color: #F0F9FF;
            font-weight: bold;
            font-size: 11px;
        }
        
        /* Couleurs */
        .amount-positive {
            color: #059669;
            font-weight: 600;
        }
        .amount-negative {
            color: #DC2626;
            font-weight: 600;
        }
        .amount-neutral {
            color: #3B82F6;
            font-weight: 600;
        }
        
        /* Grilles */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #D1D5DB;
            text-align: center;
            font-size: 9px;
            color: #6B7280;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            border-radius: 3px;
            font-weight: 600;
        }
        .badge-info {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- En-tête -->
        <div class="header">
            <h1>RAPPORT DE TRÉSORERIE MENSUEL</h1>
            <h2>Coordination Des Étudiant Thiessois a Bambey</h2>
            <div class="header-info">
                <div><strong>Mois :</strong> {{ ucfirst($moisNom) }} {{ $annee }}</div>
                <div><strong>Généré :</strong> {{ $dateGeneration }}</div>
                <div><span class="badge badge-info">DOCUMENT OFFICIEL</span></div>
            </div>
        </div>

        <!-- SYNTHÈSE FINANCIÈRE -->
        <div class="section">
            <div class="section-title">SYNTHÈSE FINANCIÈRE DU MOIS</div>
            <div class="summary-box">
                <div class="summary-row">
                    <span>Solde début du mois :</span>
                    <span class="amount-neutral">{{ number_format($soldeDebut, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="summary-row">
                    <span>+ Recettes (Paiements étudiants) :</span>
                    <span class="amount-positive">+{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="summary-row">
                    <span>- Dépenses (Maisons) :</span>
                    <span class="amount-negative">-{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="summary-row">
                    <span>- Paiements bailleurs :</span>
                    <span class="amount-negative">-{{ number_format($totalPaiementsBailleurs, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="summary-row total">
                    <span>= SOLDE FIN DE MOIS :</span>
                    <span class="{{ $soldeFin >= 0 ? 'amount-positive' : 'amount-negative' }}" style="font-size: 13px;">
                        {{ number_format($soldeFin, 0, ',', ' ') }} FCFA
                    </span>
                </div>
                <div class="summary-row" style="margin-top: 8px; border-top: 1px solid #DBEAFE; padding-top: 8px;">
                    <span><strong>Excédent / Déficit du mois :</strong></span>
                    <span class="{{ $excedentDeficit >= 0 ? 'amount-positive' : 'amount-negative' }}">
                        {{ $excedentDeficit >= 0 ? '+' : '' }}{{ number_format($excedentDeficit, 0, ',', ' ') }} FCFA
                    </span>
                </div>
            </div>
        </div>

        <!-- RECETTES (PAIEMENTS ÉTUDIANTS) -->
        <div class="section">
            <div class="section-title">RECETTES - PAIEMENTS ÉTUDIANTS</div>
            @if($paiementsMois && $paiementsMois->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Étudiant</th>
                            <th>Maison</th>
                            <th style="text-align: right;">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paiementsMois->take(30) as $paiement)
                        <tr>
                            <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                            <td>{{ $paiement->etudiant->nom }}</td>
                            <td>{{ $paiement->etudiant->maison->nom ?? 'N/A' }}</td>
                            <td style="text-align: right; color: #059669;">{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right;">TOTAL RECETTES :</td>
                            <td style="text-align: right; color: #059669;">{{ number_format($totalRecettes, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    </tbody>
                </table>
                @if($paiementsMois->count() > 30)
                <p style="font-size: 9px; color: #6B7280; margin-top: -10px;">
                    ... et {{ $paiementsMois->count() - 30 }} autres paiement(s)
                </p>
                @endif
            @else
                <p style="padding: 10px; background-color: #FEF3C7; border: 1px solid #FCD34D; border-radius: 3px;">
                    Aucun paiement d'étudiant ce mois.
                </p>
            @endif
        </div>

        <!-- DÉPENSES PAR TYPE -->
        <div class="section">
            <div class="section-title">DÉPENSES - DÉTAIL PAR TYPE</div>
            @if($depensesParType && $depensesParType->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Type de dépense</th>
                            <th style="text-align: right;">Montant</th>
                            <th style="text-align: right;">% du total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($depensesParType as $type => $montant)
                        <tr>
                            <td>{{ ucfirst(str_replace('_', ' ', $type)) }}</td>
                            <td style="text-align: right;">{{ number_format($montant, 0, ',', ' ') }} FCFA</td>
                            <td style="text-align: right;">{{ number_format(($montant / max($totalDepenses, 1)) * 100, 1) }}%</td>
                        </tr>
                        @endforeach
                        <tr class="total-row">
                            <td>TOTAL DÉPENSES MAISONS</td>
                            <td style="text-align: right;">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</td>
                            <td style="text-align: right;">100%</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <p style="padding: 10px; background-color: #FEF3C7; border: 1px solid #FCD34D; border-radius: 3px;">
                    Aucune dépense n'a été enregistrée ce mois.
                </p>
            @endif
        </div>

        <!-- FACTURES DÉTAILLÉES -->
        <div class="section">
            <div class="section-title">DÉTAIL DES FACTURES</div>
            @if($facturesMois && $facturesMois->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Maison</th>
                            <th>Type</th>
                            <th style="text-align: right;">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturesMois->take(25) as $facture)
                        <tr>
                            <td>{{ $facture->date_paiement->format('d/m/Y') }}</td>
                            <td>{{ $facture->maison->nom ?? 'N/A' }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $facture->type)) }}</td>
                            <td style="text-align: right;">{{ number_format($facture->montant, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right;">TOTAL :</td>
                            <td style="text-align: right;">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    </tbody>
                </table>
                @if($facturesMois->count() > 25)
                <p style="font-size: 9px; color: #6B7280; margin-top: -10px;">
                    ... et {{ $facturesMois->count() - 25 }} autre(s) facture(s)
                </p>
                @endif
            @else
                <p style="padding: 10px; background-color: #FEF3C7; border: 1px solid #FCD34D; border-radius: 3px;">
                    Aucune facture enregistrée ce mois.
                </p>
            @endif
        </div>

        <!-- PAIEMENTS AUX BAILLEURS -->
        <div class="section">
            <div class="section-title">DÉPENSES - PAIEMENTS AUX BAILLEURS</div>
            @if($paiementsBailleursMois && $paiementsBailleursMois->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Maison</th>
                            <th style="text-align: right;">Montant payé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paiementsBailleursMois as $paiement)
                        <tr>
                            <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                            <td>{{ $paiement->maison->nom ?? 'N/A' }}</td>
                            <td style="text-align: right;">{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                        </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="2" style="text-align: right;">TOTAL PAIEMENTS BAILLEURS :</td>
                            <td style="text-align: right;">{{ number_format($totalPaiementsBailleurs, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <p style="padding: 10px; background-color: #FEF3C7; border: 1px solid #FCD34D; border-radius: 3px;">
                    Aucun paiement aux bailleurs ce mois.
                </p>
            @endif
        </div>

        <!-- STATISTIQUES ÉTUDIANTS -->
        <div class="section">
            <div class="section-title">SITUATION DES ÉTUDIANTS</div>
            <div class="grid-2">
                <div>
                    <table style="margin-bottom: 0;">
                        <tr>
                            <td><strong>Nombre total :</strong></td>
                            <td style="text-align: right;">{{ $nombreEtudiants }}</td>
                        </tr>
                        <tr style="background-color: #FEE2E2;">
                            <td><strong>Étudiants débiteurs :</strong></td>
                            <td style="text-align: right; color: #DC2626;">{{ $nombreDebiteurs }}</td>
                        </tr>
                        <tr style="background-color: #F0FDF4;">
                            <td><strong>Étudiants créditeurs :</strong></td>
                            <td style="text-align: right; color: #059669;">{{ $nombreCrediteurs }}</td>
                        </tr>
                        <tr style="background-color: #EFF6FF;">
                            <td><strong>Étudiants à jour :</strong></td>
                            <td style="text-align: right;">{{ $nombreEtudiants - $nombreDebiteurs - $nombreCrediteurs }}</td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table style="margin-bottom: 0;">
                        <tr>
                            <td><strong>Total dettes :</strong></td>
                            <td style="text-align: right; color: #DC2626;">{{ number_format($totalDettes, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        <tr>
                            <td><strong>Total avances :</strong></td>
                            <td style="text-align: right; color: #059669;">{{ number_format($totalAvances, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        <tr>
                            <td><strong>Taux de recouvrement :</strong></td>
                            <td style="text-align: right;">
                                {{ number_format(($totalRecettes / max(($totalRecettes + $totalDettes), 1)) * 100, 1) }}%
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong> © {{ date('Y') }} C.E.</strong></p>
            <p>Document généré automatiquement par le système de gestion - Document confidentiel</p>
        </div>
    </div>
</body>
</html>
