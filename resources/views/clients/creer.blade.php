<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Client - Comptabilité</title>
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
                        <a class="nav-link active" href="{{ route('clients.index') }}">Clients</a>
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
                        <a class="nav-link" href="{{ route('utilisateurs.index') }}">Utilisateurs</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-12">
                <h1>Créer un Client</h1>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Retour à la liste</a>
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
                <form action="{{ route('clients.enregistrer') }}" method="POST">
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
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom') }}">
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="societe" class="form-label">Société</label>
                        <input type="text" class="form-control @error('societe') is-invalid @enderror" id="societe" name="societe" value="{{ old('societe') }}">
                        @error('societe')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone" name="telephone" value="{{ old('telephone') }}">
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" rows="3">{{ old('adresse') }}</textarea>
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="code_postal" class="form-label">Code Postal</label>
                                <input type="text" class="form-control @error('code_postal') is-invalid @enderror" id="code_postal" name="code_postal" value="{{ old('code_postal') }}">
                                @error('code_postal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ville" class="form-label">Ville</label>
                                <input type="text" class="form-control @error('ville') is-invalid @enderror" id="ville" name="ville" value="{{ old('ville') }}">
                                @error('ville')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pays" class="form-label">Pays</label>
                                <input type="text" class="form-control @error('pays') is-invalid @enderror" id="pays" name="pays" value="{{ old('pays', 'France') }}">
                                @error('pays')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="numero_tva" class="form-label">Numéro TVA</label>
                        <input type="text" class="form-control @error('numero_tva') is-invalid @enderror" id="numero_tva" name="numero_tva" value="{{ old('numero_tva') }}">
                        @error('numero_tva')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="gestionnaire_id" class="form-label">Gestionnaire <span class="text-danger">*</span></label>
                        <select class="form-select @error('gestionnaire_id') is-invalid @enderror" id="gestionnaire_id" name="gestionnaire_id" required>
                            <option value="">Sélectionner un gestionnaire</option>
                            @foreach($gestionnaires as $gestionnaire)
                                <option value="{{ $gestionnaire->id }}" {{ old('gestionnaire_id') == $gestionnaire->id ? 'selected' : '' }}>
                                    {{ $gestionnaire->prenom }} {{ $gestionnaire->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('gestionnaire_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Créer le client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
