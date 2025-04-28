# Frontend pour l'API Comptable

Ce projet est une interface utilisateur React pour l'API de gestion comptable.

## Fonctionnalités

- Authentification avec JWT
- Gestion des comptes comptables
- Enregistrement des transactions
- Consultation du journal comptable
- Génération de la balance comptable
- Interface responsive avec Material UI

## Prérequis

- Node.js 14.x ou supérieur
- npm 6.x ou supérieur
- API Comptable en cours d'exécution sur http://localhost:8000

## Installation

1. Cloner le dépôt :
```bash
git clone <url-du-depot>
cd comptable-frontend
```

2. Installer les dépendances :
```bash
npm install
```

3. Démarrer l'application en mode développement :
```bash
npm start
```

L'application sera accessible à l'adresse : http://localhost:3000

## Structure du projet

- `src/components/auth` : Composants d'authentification
- `src/components/comptes` : Composants pour la gestion des comptes
- `src/components/transactions` : Composants pour la gestion des transactions
- `src/components/journal` : Composants pour le journal comptable
- `src/components/balance` : Composants pour la balance comptable
- `src/components/layout` : Composants de mise en page
- `src/components/common` : Composants communs (alertes, chargement, etc.)
- `src/components/dashboard` : Composants pour le tableau de bord
- `src/services` : Services pour communiquer avec l'API
- `src/utils` : Utilitaires

## Utilisateurs de test

- Administrateur : admin@example.com / password
- Comptable : comptable@example.com / password
- Utilisateur : user@example.com / password

## Déploiement

Pour créer une version de production :

```bash
npm run build
```

Les fichiers de production seront générés dans le dossier `build`.

## Intégration avec l'API

Cette application frontend est conçue pour fonctionner avec l'API Comptable développée avec Laravel. Assurez-vous que l'API est en cours d'exécution sur http://localhost:8000 avant de démarrer l'application frontend.

## Licence

Ce projet est sous licence MIT.
