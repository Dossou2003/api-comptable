# API de Gestion Comptable

Cette API permet la gestion comptable avec un journal des écritures et la génération d'une balance comptable sous format Excel.

## Fonctionnalités

- Gestion des comptes comptables (création, modification, suppression)
- Enregistrement des transactions (écritures comptables)
- Journal comptable pour suivre l'historique des opérations
- Génération d'une balance comptable au format Excel
- Sécurisation avec JWT (Laravel Sanctum)
- Documentation API avec Swagger
- Interface utilisateur React

## Installation

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- Base de données (MySQL, PostgreSQL ou SQLite)
- Node.js et npm (pour le frontend)

### Étapes d'installation

1. Cloner le dépôt :
```
git clone <url-du-depot>
cd comptabilite
```

2. Installer les dépendances :
```
composer install
```

3. Copier le fichier d'environnement :
```
cp .env.example .env
```

4. Configurer la base de données dans le fichier .env :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=comptabilite
DB_USERNAME=root
DB_PASSWORD=
```

5. Générer la clé d'application :
```
php artisan key:generate
```

6. Exécuter les migrations et les seeders :
```
php artisan migrate --seed
```

7. Ou importer directement le fichier SQL :
```
mysql -u root -p comptabilite < database/data.sql
```

8. Lancer le serveur de développement :
```
php artisan serve
```

9. Installation et démarrage du frontend React :
```
cd comptable-frontend
npm install
npm start
```

## Utilisation de l'API

### Authentification

L'API utilise Laravel Sanctum pour l'authentification. Pour obtenir un token :

```
POST /api/login
{
    "email": "admin@example.com",
    "password": "password"
}
```

Utilisez le token reçu dans l'en-tête Authorization pour les requêtes suivantes :
```
Authorization: Bearer <token>
```

### Endpoints disponibles

#### Comptes comptables
- GET /api/comptes - Liste des comptes
- POST /api/comptes - Créer un compte
- GET /api/comptes/{id} - Détails d'un compte
- PUT /api/comptes/{id} - Modifier un compte
- DELETE /api/comptes/{id} - Supprimer un compte

#### Transactions
- GET /api/transactions - Liste des transactions
- POST /api/transactions - Enregistrer une transaction (réservé aux comptables)
- GET /api/transactions/{id} - Détails d'une transaction

#### Journal comptable
- GET /api/journal - Historique des écritures

#### Balance comptable
- GET /api/export-balance - Générer un fichier Excel avec la balance comptable
- GET /api/export-balance/csv - Générer un fichier CSV avec la balance comptable

### Documentation API

La documentation complète de l'API est disponible à l'adresse :
```
/api/documentation
```

## Gestion des rôles

L'API implémente un système de rôles pour contrôler l'accès aux fonctionnalités :

- **Administrateur** : Accès complet à toutes les fonctionnalités
- **Comptable** : Peut créer des transactions, mais ne peut pas supprimer de comptes
- **Utilisateur** : Peut consulter les données, mais ne peut pas les modifier

## Utilisateurs de test

- Administrateur : admin@example.com / password
- Comptable : comptable@example.com / password

## Exemples d'utilisation

### Authentification

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "password"}'
```

### Lister les comptes

```bash
curl -X GET http://127.0.0.1:8000/api/comptes \
  -H "Authorization: Bearer <token>"
```

### Créer un compte

```bash
curl -X POST http://127.0.0.1:8000/api/comptes \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{
    "nom": "Banque XYZ",
    "code": "512200",
    "type": "actif",
    "solde": 0
  }'
```

### Créer une transaction

```bash
curl -X POST http://127.0.0.1:8000/api/transactions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{
    "date": "2023-05-15",
    "description": "Paiement facture client",
    "compte_debit_id": 1,
    "compte_credit_id": 3,
    "montant": 500
  }'
```

### Exporter la balance comptable

```bash
curl -X GET http://127.0.0.1:8000/api/export-balance \
  -H "Authorization: Bearer <token>" \
  -o balance.html
```

## Exemple de fichier Excel généré

Un exemple de fichier Excel généré est disponible dans le dossier `examples/balance_comptable.xlsx`.

## Interface utilisateur React

L'application inclut une interface utilisateur React qui permet de :

- Se connecter avec différents rôles d'utilisateurs
- Gérer les comptes comptables
- Enregistrer des transactions
- Consulter le journal comptable
- Exporter la balance comptable

Pour accéder à l'interface, lancez le frontend React et accédez à http://localhost:3000.

## Tests

Pour exécuter les tests unitaires et d'intégration :

```bash
php artisan test
```

## Licence

Ce projet est sous licence MIT.
