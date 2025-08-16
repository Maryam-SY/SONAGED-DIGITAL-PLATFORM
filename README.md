# 🏗️ SONAGED Digital Platform

Plateforme numérique intelligente pour la gestion des déchets au Sénégal.

## 📋 Vue d'Ensemble

SONAGED Digital Platform est une solution complète comprenant :

- **📱 PWA EcoCitizen** : Application mobile pour les citoyens
- **🖥️ Dashboard Admin** : Interface web pour les gestionnaires SONAGED
- **🤝 Portail Partenaires** : Interface web pour les acteurs de l'économie circulaire

## 🛠️ Stack Technologique

- **Backend** : Laravel 11 + MySQL 8
- **Frontend** : Angular 17 + TypeScript
- **APIs Externes** : Google Maps, OpenAI, Firebase
- **Architecture** : API REST + SPA découplées

## 🚀 Démarrage Rapide

### Prérequis

- PHP 8.2+
- Node.js 18+
- MySQL 8.0+
- Composer
- Angular CLI

### Installation

1. **Cloner le projet**
```bash
git clone <repository-url>
cd SonagedDigitalPlatform
```

2. **Initialiser la base de données**
```bash
cd sonaged-api
./init-database.ps1
```

3. **Installer les dépendances frontend**
```bash
cd ../sonaged-frontend
npm install
```

4. **Démarrer l'application**
```bash
cd ..
./start-sonaged.ps1
```

## 🔑 Configuration des APIs

Avant d'utiliser l'application, configurez les clés API :

1. **Suivez le guide** : `CONFIGURATION_API_KEYS.md`
2. **Modifiez** : `sonaged-frontend/src/environments/environment.ts`
3. **Testez** : Vérifiez la console du navigateur

## 👥 Comptes de Test

L'application inclut des comptes de test préconfigurés :

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| 👨‍💼 Admin | admin@sonaged.sn | admin123 |
| 🤝 Partenaire | partenaire@sonaged.sn | partenaire123 |
| 👤 Citoyen | citoyen@sonaged.sn | citoyen123 |

## 🌐 Accès aux Interfaces

- **PWA EcoCitizen** : http://localhost:4200
- **Dashboard Admin** : http://localhost:4200/admin
- **Portail Partenaires** : http://localhost:4200/partenaires
- **API Laravel** : http://localhost:8000

## 📁 Structure du Projet

```
SonagedDigitalPlatform/
├── sonaged-api/                 # Backend Laravel
│   ├── app/
│   │   ├── Http/Controllers/    # Contrôleurs API
│   │   ├── Models/              # Modèles Eloquent
│   │   └── Services/            # Services métier
│   ├── database/
│   │   ├── migrations/          # Migrations DB
│   │   └── seeders/             # Seeders
│   └── routes/api.php           # Routes API
├── sonaged-frontend/            # Frontend Angular
│   ├── src/
│   │   ├── app/
│   │   │   ├── core/            # Services, guards, interceptors
│   │   │   ├── features/        # Modules fonctionnels
│   │   │   └── shared/          # Composants partagés
│   │   └── environments/        # Configuration
│   └── package.json
├── CONFIGURATION_API_KEYS.md    # Guide configuration APIs
├── init-database.ps1            # Script initialisation DB
└── start-sonaged.ps1           # Script démarrage
```

## 🔧 Développement

### Backend (Laravel)

```bash
cd sonaged-api

# Démarrer le serveur de développement
php artisan serve

# Exécuter les migrations
php artisan migrate

# Exécuter les seeders
php artisan db:seed

# Générer un nouveau contrôleur
php artisan make:controller NomController --api
```

### Frontend (Angular)

```bash
cd sonaged-frontend

# Démarrer le serveur de développement
npm start

# Construire pour la production
npm run build

# Générer un nouveau composant
ng generate component nom-du-composant
```

## 🧪 Tests

```bash
# Tests backend
cd sonaged-api
php artisan test

# Tests frontend
cd sonaged-frontend
npm test
```

## 📊 Fonctionnalités Principales

### PWA EcoCitizen
- ✅ Signalement géolocalisé de déchets
- ✅ Upload d'images avec analyse IA
- ✅ Système de gamification (points/badges)
- ✅ Mode offline
- ✅ Notifications push
- ✅ Interface multilingue

### Dashboard Admin
- ✅ Tableaux de bord KPI temps réel
- ✅ Gestion des signalements
- ✅ Planification des collectes
- ✅ Analytics et reporting
- ✅ Gestion des partenaires

### Portail Partenaires
- ✅ Marketplace de déchets
- ✅ Système d'enchères
- ✅ Certification et traçabilité
- ✅ Géolocalisation des gisements

## 🔒 Sécurité

- Authentification JWT avec Laravel Sanctum
- Validation des données côté serveur
- Protection CORS configurée
- Hachage sécurisé des mots de passe
- Gestion des rôles et permissions

## 📈 Performance

- Lazy loading Angular
- Cache Redis pour les sessions
- Optimisation des requêtes MySQL
- Service Worker pour le mode offline
- Compression des assets

## 🚀 Déploiement

### Production

1. **Configurer les variables d'environnement**
2. **Construire le frontend** : `npm run build`
3. **Configurer le serveur web** (Apache/Nginx)
4. **Configurer la base de données MySQL**
5. **Configurer Redis** (optionnel)

### Docker (Optionnel)

```bash
# Construire les images
docker-compose build

# Démarrer les services
docker-compose up -d
```

## 📞 Support

Pour toute question ou problème :

1. Consultez la documentation technique
2. Vérifiez les logs d'erreur
3. Contactez l'équipe de développement

## 📄 Licence

Ce projet est développé pour SONAGED (Société Nationale de Gestion des Déchets).

---

**Note** : Assurez-vous de configurer les clés API avant d'utiliser l'application en production.

