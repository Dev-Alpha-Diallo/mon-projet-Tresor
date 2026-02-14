# Structure Admin/Client Organization

## 📁 Vue Structure (Blade Templates)

```
resources/views/
├── admin/                              ← Toutes les vues Admin
│   ├── bailleurs/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── dashboard/
│   │   └── index.blade.php
│   ├── etudiants/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── show.blade.php
│   │   └── pdf/
│   │       ├── liste-complete.blade.php
│   │       └── liste-debiteurs.blade.php
│   ├── factures/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── show.blade.php
│   │   └── pdf.blade.php
│   ├── maisons/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── paiements/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── paiements_bailleurs/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   └── rapports/
│       ├── index.blade.php
│       ├── mensuel.blade.php
│       ├── trimestriel.blade.php
│       └── annuel.blade.php
│
├── client/                             ← Pour future interface client
│   └── (à développer)
│
├── auth/                               ← Partagé (login, register)
│   └── login.blade.php
│
├── layouts/                            ← Partagé (templates de base)
│   └── app.blade.php
│
└── welcome.blade.php                   ← Page d'accueil publique
```

## 🎯 Routes Structure

```
routes/
├── web.php                             ← Point d'entrée principal
│   ├── Routes publiques: /login, /logout
│   ├── Routes admin (préfixe /admin)
│   └── Routes client (préfixe /client)
│
├── admin.php                           ← Toutes les routes admin
│   └── Préfixe: /admin → Noms: admin.*
│
└── client.php                          ← Routes client (à développer)
    └── Préfixe: /client → Noms: client.*
```

## 🎯 Controllers Structure

```
app/Http/Controllers/
├── Admin/                              ← Admin controllers
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── EtudiantController.php
│   ├── PaiementController.php
│   ├── FactureController.php
│   ├── MaisonController.php
│   ├── BailleurController.php
│   ├── PaiementBailleurController.php
│   └── RapportController.php
│
├── Client/                             ← Pour future interface client
│   └── (à créer)
│
└── Controller.php                      ← Classe de base
```

## 📝 Conventions de Nommage

### Routes Admin
```php
// Routes
route('admin.dashboard')
route('admin.etudiants.index')
route('admin.etudiants.create')
route('admin.etudiants.store')
route('admin.etudiants.show', $id)
route('admin.etudiants.edit', $id)
route('admin.etudiants.update', $id)
route('admin.etudiants.destroy', $id)
route('admin.etudiants.search')
route('admin.etudiants.export.tous')
route('admin.etudiants.export.debiteurs')
```

### Vues Admin
```php
// Dans les controllers
view('admin.etudiants.index')
view('admin.etudiants.create')
view('admin.etudiants.edit')
view('admin.etudiants.show')

// Pour les PDFs
\PDF::loadView('admin.etudiants.pdf.liste-complete')
```

### Routes Client (À venir)
```php
// Routes
route('client.solde')
route('client.paiements.history')
route('client.profile.edit')
```

### Vues Client (À venir)
```php
// Dans les controllers
view('client.solde.index')
view('client.paiements.history')
view('client.profile.edit')
```

## ✅ Migration Complétée

- [x] Créer dossier `/admin` dans `resources/views`
- [x] Déplacer tous les dossiers de contenu vers `admin/`
- [x] Mettre à jour les appels `view()` dans les controllers Admin
- [x] Mettre à jour les appels `loadView()` pour les PDFs
- [x] Organiser les routes dans `routes/admin.php`
- [x] Créer `routes/client.php` pour les futures routes client
- [x] Utiliser le préfixe `/admin` pour les URLs admin
- [x] Utiliser le préfixe `admin.` pour les noms de routes

## 🚀 Prochaines Étapes

Pour ajouter les routes et vues client:

1. **Créer les controllers client:**
   ```bash
   php artisan make:controller Client/SoldeController
   php artisan make:controller Client/HistoriqueController
   ```

2. **Ajouter les routes dans `routes/client.php`:**
   ```php
   Route::get('/', [SoldeController::class, 'show'])->name('solde');
   Route::get('/historique', [HistoriqueController::class, 'index'])->name('historique');
   ```

3. **Créer les vues client:**
   ```
   resources/views/client/
   ├── solde/
   │   └── show.blade.php
   ├── historique/
   │   └── index.blade.php
   └── layout.blade.php
   ```

4. **Accès:**
   - Admin: `/admin/*`
   - Client: `/client/*`
