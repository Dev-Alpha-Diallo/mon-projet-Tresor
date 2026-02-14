# Redis Configuration & Usage

## Overview

Redis est activé comme **cache**, **session store** et **queue driver** pour améliorer les performances en production.

## Docker Setup

### Démarrer Redis

```bash
# Depuis mon-projet/
docker-compose up -d redis
```

ou inclus avec l'app complète :

```bash
docker-compose up -d
```

Vérifier que Redis tourne :
```bash
docker ps | grep redis
```

### Connexion à Redis

```bash
docker exec -it tresorerie-redis redis-cli
```

Commandes utiles :
```redis
PING                  # Tester connection
KEYS *                # Afficher toutes les clés
FLUSHALL              # Vider le cache (⚠️ attention)
INFO                  # Statistiques
```

## Laravel Configuration

### .env Variables (Docker)

```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis              # nom du service Docker
REDIS_PASSWORD=null           # ou "password" si protégé
REDIS_PORT=6379               # port standard
```

### .env Variables (Hors Docker)

Si vous tournez Laravel sans Docker (dev local) :

```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1  ou localhost
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Installer Redis localement (Windows/Mac/Linux) :
- **Windows** : `choco install redis` ou utiliser WSL
- **Mac** : `brew install redis` puis `brew services start redis`
- **Linux** : `sudo apt install redis-server`

## Usage Examples

### 1. Mettre en Cache un Résultat

```php
use Illuminate\Support\Facades\Cache;

// Cacher pour 1 heure (3600 sec)
$results = Cache::remember('rapport_mensuel_2026_02', 3600, function () {
    return RapportService::genererRapportMensuel(2, 2026);
});

// Ou simplement mettre en cache
Cache::put('key', $value, 3600);

// Récupérer
$value = Cache::get('key');

// Oublier
Cache::forget('key');
```

### 2. Invalider Cache au Besoin

```php
// Après une action (paiement, facture, etc.)
public function store(Request $request)
{
    // ... créer paiement ...
    
    // Invalider les rapports en cache
    Cache::forget('rapport_mensuel_*');  // ⚠️ pas wildcard direct
    Cache::flush();  // vider complètement (ok pour dev)
    
    return redirect();
}
```

### 3. Queue Jobs (Arrière-plan)

```php
// Envoyer un job en arrière-plan (queue)
GeneratePdfJob::dispatch($paiement)->delay(now()->addSeconds(5));

// Ou exécuter synchrone en dev :
// .env: QUEUE_CONNECTION=sync
```

## Déploiement Production

### 1. Installer Redis sur serveur

```bash
# Ubuntu/Debian
sudo apt update
sudo apt install redis-server

# Démarrer et enable
sudo systemctl start redis-server
sudo systemctl enable redis-server

# Vérifier
redis-cli PING  # doit répondre PONG
```

### 2. Sécuriser Redis

```bash
# Éditer /etc/redis/redis.conf
sudo nano /etc/redis/redis.conf

# Ajouter password (recommandé)
requirepass yourSecurePassword

# Bind au localhost (ou adresse serveur)
bind 127.0.0.1

# Redémarrer
sudo systemctl restart redis-server
```

### 3. Configurer Laravel (.env prod)

```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=yourSecurePassword
REDIS_PORT=6379
```

### 4. Déploiement avec docker-compose (recommandé)

Voir `docker-compose.yml` — Redis tourne en conteneur assurant portabilité.

## Monitoring & Logs

### Vérifier utilisation Redis

```bash
# Depuis terminal
redis-cli INFO memory

# Affiche :
# used_memory: 1M
# used_memory_percent: 0.01%
```

### Logs Laravel

```bash
# Erreurs cache/queue dans logs
tail -f storage/logs/laravel.log
```

### Problèmes Courants

**Redis not connecting:**
- Vérifier `REDIS_HOST` (docker: `redis`, local: `127.0.0.1`)
- Vérifier `REDIS_PORT` (par défaut `6379`)
- Test: `redis-cli PING`

**Cache pas invalidé:**
- Utiliser `Cache::flush()` ou `Cache::forget('key')`
- Eviter wildcard patterns — trop lent sur gros datasets

**Queue not running:**
- Lancer worker : `php artisan queue:work redis`
- En produit: supervisor ou systemd pour daemon

## Optimisations pour Votre App

### Cacher les Rapports

```php
// app/Services/RapportService.php
public function genererRapportMensuel($mois, $annee)
{
    $cacheKey = "rapport_mensuel_{$annee}_{$mois}";
    
    return Cache::remember($cacheKey, 86400, function () use ($mois, $annee) {
        return $this->collecterDonneesMensuelles($mois, $annee);
    });
}
```

### Cacher Liste Bailleurs/Maisons

```php
// app/Http/Controllers/BailleurController.php
public function index()
{
    $bailleurs = Cache::remember('bailleurs_list', 3600, function () {
        return Bailleur::with('maisons')->latest()->paginate(20);
    });
    return view('bailleurs.index', compact('bailleurs'));
}
```

### Invalider au Changement

```php
public function store(Request $request)
{
    $bailleur = Bailleur::create($request->validated());
    Cache::forget('bailleurs_list');  // Invalider
    return redirect();
}
```

## Performance Impact

**Avant Redis** (file cache) :
- Cache miss → 200-500ms (recalculer)
- Session load → 50ms (fichier disque)

**Après Redis** :
- Cache hit → <5ms (mémoire)
- Cache miss → 200-500ms (recalculer)
- Session load → <5ms (mémoire)

**Résultat:** Réduction latence **20-50x sur hits** = app 2-5x plus rapide pour utilisateurs récurrents.

## Commandes Artisan Utiles

```bash
# Vider tout le cache
php artisan cache:clear

# Vider les sessions
php artisan session:clear

# Vider queues
php artisan queue:clear

# Démarrer queue worker
php artisan queue:work redis --timeout=60

# Daemoniser (production)
# Voir supervisor ou systemd
```
