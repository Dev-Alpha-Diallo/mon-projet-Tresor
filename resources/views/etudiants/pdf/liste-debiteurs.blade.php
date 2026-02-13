<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $titre }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px solid #EF4444; }
        .header h1 { color: #DC2626; margin: 0 0 5px 0; font-size: 20px; }
        .header p { margin: 2px 0; color: #666; }
        .alert { background-color: #FEE2E2; padding: 10px; border-left: 4px solid #EF4444; margin-bottom: 15px; color: #991B1B; }
        .summary { background-color: #FEF3C7; padding: 10px; border-left: 4px solid #F59E0B; margin-bottom: 15px; }
        .summary-item { display: inline-block; margin-right: 25px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th { background-color: #DC2626; color: white; padding: 6px 8px; text-align: left; font-weight: bold; font-size: 10px; }
        table td { padding: 5px 8px; border-bottom: 1px solid #E5E7EB; font-size: 10px; }
        table tr:nth-child(even) { background-color: #FEF2F2; }
        .amount-negative { color: #DC2626; font-weight: bold; }
        .highlight-row { background-color: #FECACA !important; }
        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #6B7280; border-top: 1px solid #D1D5DB; padding-top: 8px; }
        .page-number:after { content: counter(page); }
        .total-row { background-color: #FEE2E2; font-weight: bold; border-top: 2px solid #DC2626; }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>⚠️ {{ $titre }}</h1>
        <p><strong>Trésorerie Amicale C.E.T</strong></p>
        <p>Généré le : {{ $dateGeneration }}</p>
    </div>

    <!-- Alerte -->
    <div class="alert">
        <strong>⚠️ ATTENTION :</strong> Ce document contient la liste des étudiants en situation de dette.
    </div>

    <!-- Résumé -->
    <div class="summary">
        <div class="summary-item">
            <strong>Nombre de débiteurs :</strong> {{ $nombreTotal }}
        </div>
        <div class="summary-item">
            <strong>Total des dettes :</strong> {{ number_format($totalDettes, 0, ',', ' ') }} FCFA
        </div>
        <div class="summary-item">
            <strong>Dette moyenne :</strong> {{ $nombreTotal > 0 ? number_format($totalDettes / $nombreTotal, 0, ',', ' ') : 0 }} FCFA
        </div>
    </div>

    <!-- Tableau -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Nom complet</th>
                <th style="width: 15%;">Filière</th>
                <th style="width: 20%;">Maison</th>
                <th style="width: 10%;">Chambre</th>
                <th style="width: 12%;">Loyer/mois</th>
                <th style="width: 13%;">Dette</th>
            </tr>
        </thead>
        <tbody>
            @foreach($etudiants as $index => $etudiant)
            <tr class="{{ abs($etudiant->solde) > 20000 ? 'highlight-row' : '' }}">
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $etudiant->nom }}</strong></td>
                <td>{{ $etudiant->filiere }}</td>
                <td>{{ $etudiant->maison ? $etudiant->maison->nom : 'N/A' }}</td>
                <td>{{ $etudiant->chambre }}</td>
                <td style="text-align: right;">{{ number_format($etudiant->loyer_mensuel, 0, ',', ' ') }} F</td>
                <td style="text-align: right;" class="amount-negative">
                    {{ number_format($etudiant->solde, 0, ',', ' ') }} F
                </td>
            </tr>
            @endforeach
            
            <!-- Total -->
            <tr class="total-row">
                <td colspan="6" style="text-align: right; padding: 8px;"><strong>TOTAL DES DETTES :</strong></td>
                <td style="text-align: right; padding: 8px;" class="amount-negative">
                    <strong>{{ number_format($totalDettes, 0, ',', ' ') }} FCFA</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Recommandations -->
    <div style="margin-top: 15px; padding: 10px; background-color: #F3F4F6; border-left: 4px solid #6B7280;">
        <p style="margin: 0; font-size: 10px;"><strong>📋 Recommandations :</strong></p>
        <ul style="margin: 5px 0; padding-left: 20px; font-size: 10px;">
            <li>Contacter les étudiants en rouge (dette > 20 000 FCFA) en priorité</li>
            <li>Établir un plan de paiement pour les dettes importantes</li>
            <li>Envoyer des rappels réguliers</li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>© {{ date('Y') }} Trésorerie C.E.T</strong> - Document strictement confidentiel</p>
        <p>Page <span class="page-number"></span></p>
    </div>
</body>
</html>