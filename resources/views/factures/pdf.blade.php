<h1>Facture #{{ $facture->id }}</h1>
<p>Maison : {{ $facture->maison->nom ?? 'Inconnue' }}</p>
<p>Mois : {{ \Carbon\Carbon::parse($facture->mois)->format('M Y') }}</p>
<p>Montant : {{ number_format($facture->montant, 0, ',', ' ') }} F</p>
<p>Statut : {{ ucfirst($facture->statut) }}</p>
<p>Date d'échéance : {{ \Carbon\Carbon::parse($facture->date_echeance)->format('d/m/Y') }}</p>
