# 🌱 SONAGED Digital Platform - Frontend

## 📋 Vue d'ensemble

**SONAGED** (Société Nationale de Gestion des Déchets) est une plateforme digitale complète pour la gestion intelligente des déchets au Sénégal. Cette application web administrative offre une interface centralisée pour la supervision des opérations, le monitoring environnemental et la conformité ISO 14001.

## ✨ Fonctionnalités Principales

### 🎯 Dashboard Administratif
- **KPI en temps réel** : Taux de collecte, conformité ISO 14001, véhicules actifs
- **Graphiques interactifs** : Évolution des performances avec Chart.js
- **Système d'alertes** : Notifications automatiques et gestion des incidents
- **Cartographie opérationnelle** : Suivi GPS des véhicules et équipes

### 🌍 Monitoring Environnemental
- **Capteurs IoT** : Qualité de l'air, eau, niveau sonore, déchets
- **Alertes automatiques** : Seuils configurables et notifications
- **Indicateurs Technopole** : Suivi des zones sensibles

### 🚛 Gestion Opérationnelle
- **Supervision des équipes** : Localisation et statut en temps réel
- **Planification des tournées** : Optimisation des routes de collecte
- **Gestion des incidents** : Suivi et résolution des problèmes

### 📊 Conformité & Reporting
- **ISO 14001** : Traçabilité complète et indicateurs de performance
- **Rapports automatisés** : Génération PDF/CSV/JSON
- **Audit trail** : Historique des actions et modifications

## 🏗️ Architecture Technique

### Frontend
- **Framework** : Angular 17 (Standalone Components)
- **UI Components** : Angular Material Design
- **Charts** : Chart.js avec composants réutilisables
- **Maps** : Google Maps API intégrée
- **State Management** : RxJS avec services injectables

### Composants Partagés
- `KpiCardComponent` : Affichage des indicateurs clés
- `ChartComponent` : Graphiques Chart.js configurables
- `AlertPanelComponent` : Gestion des alertes et notifications
- `MapComponent` : Cartographie interactive avec Google Maps

### Services Core
- `DashboardService` : Données du tableau de bord
- `AlertService` : Gestion des alertes système
- `AuthService` : Authentification et autorisation
- `AnalyticsService` : Données d'analyse et rapports

## 🚀 Installation et Démarrage

### Prérequis
- Node.js 18+ et npm
- Angular CLI 17+
- Git

### Installation
```bash
# Cloner le projet
git clone [URL_DU_REPO]
cd SonagedDigitalPlatform/sonaged-frontend

# Installer les dépendances
npm install

# Vérifier la configuration
npm run type-check
```

### Démarrage
```bash
# Mode développement (port 4201)
npm run start:admin

# Build de production
npm run build:admin

# Build avec optimisation
npm run build:admin --prod
```

## 📁 Structure du Projet

```
sonaged-frontend/
├── src/
│   ├── app/
│   │   ├── core/
│   │   │   ├── services/          # Services métier
│   │   │   ├── interfaces/        # Types TypeScript
│   │   │   └── interceptors/      # Intercepteurs HTTP
│   │   ├── shared/
│   │   │   └── components/        # Composants réutilisables
│   │   └── features/
│   │       ├── admin/             # Modules administratifs
│   │       │   ├── dashboard/     # Tableau de bord principal
│   │       │   ├── cartographie/  # Cartographie opérationnelle
│   │       │   ├── monitoring/    # Monitoring environnemental
│   │       │   ├── operations/    # Gestion opérationnelle
│   │       │   ├── supervision/   # Supervision terrain
│   │       │   ├── compliance/    # Conformité ISO 14001
│   │       │   ├── system/        # Administration système
│   │       │   ├── users/         # Gestion des utilisateurs
│   │       │   ├── settings/      # Configuration
│   │       │   └── iot/           # IoT et capteurs
│   │       ├── auth/              # Authentification
│   │       └── analytics/         # Analyses et rapports
│   ├── environments/              # Configuration par environnement
│   └── assets/                    # Ressources statiques
├── package.json
├── angular.json
└── tsconfig.json
```

## 🔧 Configuration

### Variables d'Environnement
```typescript
// src/environments/environment.admin.ts
export const environment = {
  production: false,
  apiUrl: 'http://127.0.0.1:8000/api',
  appName: 'SONAGED Admin - Gestion Centralisée',
  port: 4201,
  
  // API Keys (à configurer)
  openaiApiKey: 'DEMO_KEY',
  googleMapsApiKey: 'DEMO_GOOGLE_MAPS_KEY',
  
  // Modules SONAGED
  modules: {
    birmax: { enabled: true, apiUrl: 'http://127.0.0.1:8001/api' },
    digibac: { enabled: true, apiUrl: 'http://127.0.0.1:8002/api' },
    bipro: { enabled: true, apiUrl: 'http://127.0.0.1:8003/api' },
    alloDechets: { enabled: true, apiUrl: 'http://127.0.0.1:8004/api' }
  }
};
```

### Dépendances Principales
```json
{
  "dependencies": {
    "@angular/core": "^17.0.0",
    "@angular/material": "^17.0.0",
    "@angular/google-maps": "^17.0.0",
    "chart.js": "^4.0.0",
    "rxjs": "^7.0.0"
  }
}
```

## 🧪 Tests et Validation

### Vérification des Types
```bash
npm run type-check
```

### Tests Unitaires
```bash
npm run test
```

### Tests E2E
```bash
npm run e2e
```

### Linting
```bash
npm run lint
```

## 📊 État Actuel du Projet

### ✅ **Complété**
- [x] Architecture Angular 17 complète
- [x] Composants partagés (KPI, Chart, Alert, Map)
- [x] Dashboard administratif fonctionnel
- [x] Services core avec données de démonstration
- [x] Tous les modules admin créés
- [x] Compilation sans erreurs
- [x] Démarrage de l'application réussi

### 🔄 **En Cours**
- [ ] Intégration des vraies API backend
- [ ] Configuration des clés API (Google Maps, OpenAI)
- [ ] Tests unitaires et d'intégration
- [ ] Optimisation des performances

### 📋 **À Faire**
- [ ] Déploiement en production
- [ ] Monitoring et logging
- [ ] Documentation utilisateur
- [ ] Formation des équipes

## 🌐 Déploiement

### Build de Production
```bash
npm run build:admin --prod
```

### Serveur de Production
```bash
# Utiliser un serveur web (nginx, Apache)
# ou déployer sur une plateforme cloud
```

## 🔐 Sécurité

- **Authentification** : JWT avec refresh tokens
- **Autorisation** : Rôles et permissions granulaires
- **HTTPS** : Obligatoire en production
- **Validation** : Sanitisation des entrées utilisateur
- **Audit** : Logs de toutes les actions sensibles

## 📞 Support et Contact

### Équipe Technique
- **Lead Developer** : [Nom]
- **Architect** : [Nom]
- **DevOps** : [Nom]

### Documentation
- **API Docs** : [Lien]
- **User Manual** : [Lien]
- **Technical Specs** : [Lien]

## 📈 Roadmap

### Phase 1 (Actuelle) ✅
- Architecture de base
- Dashboard principal
- Composants partagés

### Phase 2 (Q1 2024)
- Intégration backend
- Tests complets
- Optimisation

### Phase 3 (Q2 2024)
- Fonctionnalités avancées
- Mobile app
- IA et ML

## 🎯 Objectifs

1. **Réduction des coûts** : Optimisation des tournées de collecte
2. **Amélioration de la qualité** : Monitoring en temps réel
3. **Conformité réglementaire** : ISO 14001 et normes locales
4. **Transparence citoyenne** : Données ouvertes et rapports
5. **Innovation technologique** : IoT, IA, et analytics avancés

---

**SONAGED Digital Platform** - Transformant la gestion des déchets au Sénégal 🌱🇸🇳

