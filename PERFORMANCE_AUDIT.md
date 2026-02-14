Rapport d'audit performance — actions recommandées

Résumé
------
J'ai construit les assets Vite et audité le code pour repérer les usages susceptibles de charger trop de données en mémoire (->get(), ::all()). Voici les fichiers concernés et les recommandations concrètes pour chaque cas.

Build assets
------------
- `npm install` && `npm run build` exécutés avec succès — les fichiers sont en `public/build/`.

Trouvés (occurrences importantes)
---------------------------------
(app/Services/RapportService.php)
- Plusieurs appels à `->get()` sur `Etudiant`, `Maison`, `Paiement`, `Facture`.
- Raison: rapports qui calculent totaux et statisitiques. Risque: charges mémoire si la base est volumineuse.
- Recommandation: utiliser des requêtes agrégées (sum, count) côté SQL plutôt que récupérer toutes les lignes; pour listes longues, utiliser `cursor()`/`chunk()` pour traitement en streaming; mettre en cache les résultats de rapport (Cache::remember).

(app/Http/Controllers/EtudiantController.php)
- `Maison::all()` utilisé plusieurs fois pour remplir selects.
- Recommandation: remplacer par `Maison::orderBy('nom')->get()` (petite amélioration de tri) ou charger asynchrone via endpoint `/etudiants/search` si la liste dépasse quelques centaines.

(app/Http/Controllers/PaiementBailleurController.php)
- `Bailleur::orderBy('nom')->get()` et `Maison::orderBy('nom')->get()` dans create/edit — ok pour listes petites, sinon remplacer par recherche asynchrone.

(app/Http/Controllers/PaiementController.php)
- J'ai déjà évité `Etudiant::get()` dans les formulaires (préremplissage seulement) et ajouté recherche asynchrone.
- `Maison::all()` est utilisé pour remplir un select; si le nombre de maisons est grand, remplacer par `orderBy('nom')->get()` ou endpoint asynchrone.

(app/Http/Controllers/FactureController.php)
- `Maisons` et `Etudiants` chargés complètement pour formulaire de création.
- Recommandation: pour `etudiants` utiliser recherche asynchrone (comme pour paiements); pour `maisons`, utiliser `orderBy()->get()` ou endpoint asynchrone.

Autres remarques
----------------
- Beaucoup d'appels `->get()` dans `RapportService` semblent ciblés par période — privilégier requêtes SQL (count/sum) et pré-chargement (`with`) uniquement des relations nécessaires.
- Les index recommandés ont été ajoutés via la migration `2026_02_13_000001_add_indexes_for_performance.php` (etudiants.maison_id, etudiants.chambre, paiements.date_paiement, factures.date_paiement, factures.statut).

Propositions d'actions immédiates (prioritaires)
------------------------------------------------
1) Remplacer chargements complets dans les formulaires par recherche asynchrone (déjà fait pour paiements). Étendre aux formulaires `factures.create`, `paiements-bailleurs.create/edit` si nécessaire.
2) Refactorer `RapportService` pour utiliser agrégations SQL + streaming (cursor/chunk) au lieu de `get()` complet.
3) Activer cache pour rapports lourds (Redis) et stocker résultats quelques minutes/heures selon besoin.
4) Ajouter monitoring (Sentry) et profiler (Blackfire/XHProf) pour détecter requêtes lentes en prod.

Commandes utiles
----------------
Exécuter les migrations d'index (déjà exécutées par vous) :

```bash
php artisan migrate
```

Commands de production (rappel):

```bash
composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force
php artisan storage:link --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Étapes suivantes que je peux appliquer automatiquement
---------------------------------------------------
- Automatiser la conversion des formulaires restants (`factures.create`, `paiements-bailleurs`) vers recherche asynchrone.
- Refactorer `RapportService` pour remplacer `->get()` par `chunk()`/`cursor()` et proposer un patch.
- Mettre en place des tests rapides pour vérifier le comportement des endpoints asynchrones.

Dites-moi quelle action vous voulez que j'applique maintenant (ex: convertir `factures.create` en recherche asynchrone, refactoriser `RapportService`, ou générer PR avec ces changements).