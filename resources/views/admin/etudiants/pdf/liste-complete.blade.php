<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $titre }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 3px solid #4F46E5; }
        .header h1 { color: #4338CA; margin: 0 0 5px 0; font-size: 18px; }
        .header p { margin: 2px 0; color: #666; font-size: 10px; }
        .summary { background-color: #EEF2FF; padding: 8px; border-left: 4px solid #4F46E5; margin-bottom: 12px; }
        .summary-item { display: inline-block; margin-right: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table th { background-color: #4F46E5; color: white; padding: 5px 4px; text-align: left; font-weight: bold; font-size: 9px; }
        table td { padding: 4px; border-bottom: 1px solid #E5E7EB; font-size: 9px; vertical-align: middle; }
        table tr:nth-child(even) { background-color: #F9FAFB; }
        .checkbox-cell { text-align: center; padding: 2px !important; }
        .checkbox { 
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1.5px solid #4F46E5;
            border-radius: 3px;
            margin: 0 1px;
            background-color: white;
        }
        .footer { margin-top: 15px; text-align: center; font-size: 8px; color: #6B7280; border-top: 1px solid #D1D5DB; padding-top: 6px; }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>{{ $titre }}</h1>
        <p><strong>COORDINATION DES ETUDIANTS DE THIES (C.E.T)</strong></p>
        <p>Généré le : {{ $dateGeneration }}</p>
    </div>

    <!-- Résumé -->
    <div class="summary">
        <div class="summary-item">
            <strong>Nombre total d'étudiants :</strong> {{ $nombreTotal }}
        </div>
    </div>

    <!-- Tableau -->
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 24%;">Nom complet</th>
                <th style="width: 16%;">Filière</th>
                <th style="width: 20%;">Maison</th>
                <th style="width: 7%;">Chambre</th>
                <th style="width: 29%; text-align: center;">Mois</th>
            </tr>
        </thead>
        <tbody>
            @foreach($etudiants as $index => $etudiant)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $etudiant->nom }}</strong></td>
                <td>{{ $etudiant->filiere }}</td>
                <td>{{ $etudiant->maison ? $etudiant->maison->nom : 'N/A' }}</td>
                <td style="text-align: center;">{{ $etudiant->chambre }}</td>
                <td class="checkbox-cell">
                    <span class="checkbox"></span>
                    <span class="checkbox"></span>
                    <span class="checkbox"></span>
                    <span class="checkbox"></span>
                    <span class="checkbox"></span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>© {{ date('Y') }} C.E.T DE BAMBEY</strong> - Document confidentiel</p>
        <p>Page <span class="page-number"></span></p>
    </div>
</body>
</html>
