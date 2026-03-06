<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #1e293b; background: #fff; }

        .header-bar { height: 6px; background: #6366f1; }

        .header { padding: 30px 40px 20px; display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e2e8f0; }
        .org-name { font-size: 22px; font-weight: bold; color: #1e293b; }
        .org-sub  { font-size: 11px; color: #6366f1; margin-top: 2px; }
        .org-info { font-size: 11px; color: #64748b; margin-top: 6px; line-height: 1.6; }

        .invoice-title { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; text-align: right; }
        .invoice-number { font-size: 28px; font-weight: bold; color: #1e293b; text-align: right; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; margin-top: 6px; }
        .badge-payee   { background: #dcfce7; color: #16a34a; }
        .badge-partiel { background: #fef9c3; color: #ca8a04; }
        .badge-impayee { background: #fee2e2; color: #dc2626; }

        .info-section { padding: 24px 40px; display: flex; justify-content: space-between; border-bottom: 1px solid #e2e8f0; }
        .info-block { flex: 1; }
        .info-label { font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 5px; }
        .info-value { font-size: 13px; font-weight: 600; color: #1e293b; }
        .info-sub   { font-size: 11px; color: #64748b; margin-top: 2px; }

        .table-section { padding: 24px 40px; }
        table { width: 100%; border-collapse: collapse; }
        thead th { font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; padding: 0 0 10px; border-bottom: 1px solid #e2e8f0; }
        thead th:last-child { text-align: right; }
        tbody td { padding: 16px 0; border-bottom: 1px solid #f1f5f9; font-size: 12px; }
        tbody td:last-child { text-align: right; }
        .desc-title { font-weight: 600; color: #1e293b; }
        .desc-sub   { font-size: 11px; color: #64748b; margin-top: 3px; }
        tfoot td { padding-top: 14px; }
        .total-label { font-size: 11px; color: #64748b; text-align: right; font-weight: 600; }
        .total-amount { font-size: 22px; font-weight: bold; color: #6366f1; text-align: right; }

        .footer { padding: 16px 40px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; }
        .footer p { font-size: 10px; color: #94a3b8; }

        .retard { color: #dc2626; font-size: 11px; margin-top: 4px; }
    </style>
</head>
<body>

    <div class="header-bar"></div>

    {{-- En-tête --}}
    <div class="header">
        <div>
            <div class="org-name">C.E.T</div>
            <div class="org-sub">Trésorerie Amicale</div>
            <div class="org-info">
                Thies, Sénégal<br>
                treso@cet.sn
            </div>
        </div>
        <div>
            <div class="invoice-title">Facture</div>
            <div class="invoice-number">#{{ str_pad($facture->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div style="text-align:right; margin-top:6px;">
                @php
                    $badgeClass = match($facture->statut) {
                        'payee'   => 'badge-payee',
                        'partiel' => 'badge-partiel',
                        default   => 'badge-impayee',
                    };
                    $badgeLabel = match($facture->statut) {
                        'payee'   => 'Payée',
                        'partiel' => 'Partielle',
                        default   => 'Impayée',
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                @if($facture->is_en_retard)
                    <div class="retard">⚠ En retard</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Infos --}}
    <div class="info-section">
        <div class="info-block">
            <div class="info-label">Maison</div>
            <div class="info-value">{{ $facture->maison->nom ?? 'Inconnue' }}</div>
            <div class="info-sub">{{ $facture->maison->adresse ?? '' }}</div>
        </div>
        <div class="info-block">
            <div class="info-label">Type</div>
            <div class="info-value">
                {{ match($facture->type) {
                    'eau'         => 'Eau',
                    'electricite' => 'Electricite',
                    'reparation'  => 'Reparation',
                    default       => 'Autre'
                } }}
            </div>
            <div class="info-sub">{{ $facture->numero_facture }}</div>
        </div>
        <div class="info-block">
            <div class="info-label">Date d'émission</div>
            <div class="info-value">{{ $facture->date_emission->format('d/m/Y') }}</div>
        </div>
        <div class="info-block">
            <div class="info-label">Date d'échéance</div>
            <div class="info-value" style="{{ $facture->is_en_retard ? 'color:#dc2626;' : '' }}">
                {{ $facture->date_echeance->format('d/m/Y') }}
            </div>
            @if($facture->date_paiement)
                <div class="info-sub" style="color:#16a34a;">
                    Payée le {{ $facture->date_paiement->format('d/m/Y') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Tableau montant --}}
    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th style="text-align:left;">Description</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="desc-title">
                            Facture {{ match($facture->type) {
                                'eau'         => 'Eau',
                                'electricite' => 'Electricite',
                                'reparation'  => 'Reparation',
                                default       => 'Autre'
                            } }}
                        </div>
                        @if($facture->description)
                            <div class="desc-sub">{{ $facture->description }}</div>
                        @endif
                    </td>
                    <td>{{ number_format($facture->montant, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="total-label">TOTAL DÛ</td>
                    <td class="total-amount">{{ number_format($facture->montant, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>© {{ date('Y') }} Trésorerie C.E.T — Document strictement confidentiel</p>
    </div>

</body>
</html>