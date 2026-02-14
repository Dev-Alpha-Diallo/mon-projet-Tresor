Guide de déploiement & optimisation (production)

But: rendre l'application Laravel propre, rapide et fonctionnelle en production.

1) Commandes à exécuter sur le serveur (exemple)

- Installer les dépendances (prod) :

```bash
composer install --no-dev --optimize-autoloader --no-interaction
npm ci --production   # ou pnpm/yarn selon votre stack
npm run build          # construit les assets (Vite) pour prod
```

- Artisan / cache / migrations :

```bash
php artisan migrate --force
php artisan storage:link --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

- Redémarrer les workers / queue :

```bash
php artisan queue:restart
# si vous utilisez Horizon: php artisan horizon:terminate
```

2) PHP & serveur

- Utiliser PHP-FPM derrière Nginx/Apache avec opcache activé
- Configurer `opcache.memory_consumption=256` et `opcache.validate_timestamps=0` en prod
- Allouer suffisamment de workers PHP-FPM

3) .env

- `APP_ENV=production`, `APP_DEBUG=false`
- Activer un driver de cache rapide (`redis`/`memcached`) et configurer `SESSION_DRIVER` sur `redis` si possible
- Configurer un `LOG_CHANNEL` approprié (daily) et limiter niveau à `warning`/`error`

4) Assets (Vite)

- Construire en mode production : `npm run build`
- Utiliser versioning / cache-busting (Vite le fait via hashed filenames)
- Servir les assets statiques via un CDN si possible

5) Composer & autoload

- Sur le serveur : `composer install --no-dev --optimize-autoloader`
- Utiliser `composer dump-autoload -o` si besoin

6) Base de données

- Ajouter des indexes pour les colonnes utilisées en WHERE/ORDER BY/jointures (ex: `maison_id`, `chambre`, `nom` selon requêtes fréquentes)
- Surveiller les requêtes lentes et créer des indexes adaptés
- Utiliser pagination/chunking pour gros jeux de résultats

7) Eloquent & performances

- Précharger relations (`with()`) pour éviter N+1
- Eviter `get()` sur de très larges ensembles; utiliser `cursor()`/`chunk()` ou pagination
- Transformer collections lourdes en jobs si traitement long

8) Cache

- Mettre en cache les résultats lourds (Cache::remember) et invalider proprement
- Mettre en cache les views/partials coûteux

9) Sécurité & hardening

- Ne pas commiter `.env` avec secrets
- Forcer HTTPS et HSTS
- Mettre à jour dépendances régulièrement

10) Monitoring & erreurs

- Ajouter Sentry/Horizon/Prometheus selon besoin
- Configurer alerting (erreurs critiques, files d'attente bloquées)

11) Checklist rapide avant bascule en prod

- [ ] `APP_DEBUG=false` dans `.env`
- [ ] Exécuter les commandes de cache et build ci-dessus
- [ ] Vérifier permissions `storage/` et `bootstrap/cache`
- [ ] Tester les pages principales et actions critiques

Notes & recommandations personnalisées

- J'ai ajouté un script Composer `composer deploy` pour exécuter les commandes clés (`migrate`, `config:cache`, `route:cache`, `view:cache`).
- Si vous voulez, je peux :
  - analyser le code pour repérer requêtes lentes et proposer indexes
  - identifier endroits susceptibles de N+1 et proposer corrections
  - ajouter CI/CD (GitHub Actions) pour build et déploiement automatisé

---
Faites-moi savoir quelle étape vous voulez que j'exécute ensuite (audit DB, corriger N+1, ajouter CI/CD, etc.).