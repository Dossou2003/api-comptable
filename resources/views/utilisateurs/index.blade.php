<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs - Comptabilité</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('tableau-de-bord') }}">Comptabilité</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tableau-de-bord') }}">Tableau de Bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clients.index') }}">Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('factures.index') }}">Factures</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('produits.index') }}">Produits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">Catégories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('utilisateurs.index') }}">Utilisateurs</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <h1>Liste des Utilisateurs</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('utilisateurs.creer') }}" class="btn btn-primary">Ajouter un utilisateur</a>
            </div>
        </div>

        <!-- Messages de succès ou d'erreur -->
        @if(session('succes'))
            <div class="alert alert-success">
                {{ session('succes') }}
            </div>
        @endif

        @if(session('erreur'))
            <div class="alert alert-danger">
                {{ session('erreur') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($utilisateurs as $utilisateur)
                            <tr>
                                <td>{{ $utilisateur->id }}</td>
                                <td>{{ $utilisateur->nom }}</td>
                                <td>{{ $utilisateur->prenom }}</td>
                                <td>{{ $utilisateur->email }}</td>
                                <td>{{ ucfirst($utilisateur->role) }}</td>
                                <td>
                                    <a href="{{ route('utilisateurs.afficher', $utilisateur) }}" class="btn btn-sm btn-info">Voir</a>
                                    <a href="{{ route('utilisateurs.modifier', $utilisateur) }}" class="btn btn-sm btn-warning">Modifier</a>
                                    <a href="{{ route('utilisateurs.supprimer', $utilisateur) }}" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucun utilisateur trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
