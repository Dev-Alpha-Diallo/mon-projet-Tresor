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
        .total-row { background-color: #FEE2E2; font-weight: bold; border-top: 2px solid #DC2626; }
    </style>
</head>
<body>

    <div class="header">
        <h1>⚠️ {{ $titre }}</h1>
        <p><strong>Trésorerie Amicale C.E.T</strong></p>
        <p>Étudiants n'ayant pas payé : <strong>{{ $moisConcerne }}</strong></p>
        <p>Généré le : {{ $dateGeneration }}</p>
    </div>

    <div class="alert">
        <strong>⚠️ ATTENTION :</strong> Liste des étudiants qui n'ont pas <strong>payé</strong> le mois de <strong>{{ $moisConcerne }}</strong>.
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Nombre de débiteurs :</strong> {{ $nombreTotal }}
        </div>
        <div class="summary-item">
            <strong>Total dettes réelles :</strong> {{ number_format($totalDettes, 0, ',', ' ') }} FCFA
        </div>
        <div class="summary-item">
            <strong>Moyenne :</strong> {{ $nombreTotal > 0 ? number_format($totalDettes / $nombreTotal, 0, ',', ' ') : 0 }} FCFA
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 26%;">Nom complet</th>
                <th style="width: 14%;">Filière</th>
                <th style="width: 20%;">Maison</th>
                <th style="width: 8%;">Chambre</th>
                <th style="width: 14%;">Loyer/mois</th>
                <th style="width: 14%;">Dette réelle</th>
            </tr>
        </thead>
        <tbody>
            @foreach($etudiants as $etudiant)
            {{-- ✅ Rouge = doit plus d'un mois (solde < -loyer) --}}
            <tr class="{{ $etudiant->solde < -$etudiant->loyer_mensuel ? 'highlight-row' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $etudiant->nom }}</strong></td>
                <td>{{ $etudiant->filiere }}</td>
                <td>{{ $etudiant->maison->nom ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $etudiant->chambre }}</td>
                <td style="text-align: right;">{{ number_format($etudiant->loyer_mensuel, 0, ',', ' ') }} F</td>
                {{-- ✅ Solde global réel --}}
                <td style="text-align: right;" class="amount-negative">
                    {{ number_format($etudiant->solde, 0, ',', ' ') }} F
                </td>
            </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="6" style="text-align: right; padding: 8px;">
                    <strong>TOTAL DETTES RÉELLES :</strong>
                </td>
                <td style="text-align: right; padding: 8px;" class="amount-negative">
                    <strong>{{ number_format($totalDettes, 0, ',', ' ') }} FCFA</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 15px; padding: 10px; background-color: #F3F4F6; border-left: 4px solid #6B7280;">
        <p style="margin: 0 0 5px 0; font-size: 10px;"><strong>📋 Recommandations :</strong></p>
        <ul style="margin: 0; padding-left: 20px; font-size: 10px;">
            <li>Envoyer des rappels de paiement individuels</li>
            <li>Établir un plan de recouvrement avant fin du mois</li>
        </ul>
    </div>

    <div class="footer">
        <p><strong>© {{ date('Y') }} Trésorerie C.E.T</strong> - Document strictement confidentiel</p>
    </div>

</body>
</html>