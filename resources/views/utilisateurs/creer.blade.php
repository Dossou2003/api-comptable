<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Utilisateur - Comptabilité</title>
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
            <div class="col-12">
                <h1>Créer un Utilisateur</h1>
                <a href="{{ route('utilisateurs.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>

        <!-- Affichage des erreurs de validation -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('utilisateurs.enregistrer') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mot_de_passe" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('mot_de_passe') is-invalid @enderror" id="mot_de_passe" name="mot_de_passe" required>
                                @error('mot_de_passe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mot_de_passe_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="mot_de_passe_confirmation" name="mot_de_passe_confirmation" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}">
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" rows="3">{{ old('adresse') }}</textarea>
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="administrateur" {{ old('role') == 'administrateur' ? 'selected' : '' }}>Administrateur</option>
                            <option value="gestionnaire" {{ old('role') == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
                            <option value="utilisateur" {{ old('role') == 'utilisateur' ? 'selected' : '' }}>Utilisateur</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
