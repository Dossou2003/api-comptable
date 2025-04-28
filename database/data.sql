-- Utilisateurs
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, created_at, updated_at) VALUES
('Admin', 'Système', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrateur', NOW(), NOW()),
('Comptable', 'Test', 'comptable@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gestionnaire', NOW(), NOW());

-- Comptes
INSERT INTO comptes (nom, code, type, solde, created_at, updated_at) VALUES
('Banque', '512', 'actif', 10000.00, NOW(), NOW()),
('Caisse', '530', 'actif', 500.00, NOW(), NOW()),
('Clients', '411', 'actif', 3000.00, NOW(), NOW()),
('Fournisseurs', '401', 'passif', 2000.00, NOW(), NOW()),
('Emprunts', '164', 'passif', 5000.00, NOW(), NOW()),
('Ventes de produits', '701', 'produit', 8000.00, NOW(), NOW()),
('Prestations de services', '706', 'produit', 4000.00, NOW(), NOW()),
('Achats de marchandises', '607', 'charge', 3000.00, NOW(), NOW()),
('Loyers', '613', 'charge', 1200.00, NOW(), NOW()),
('Salaires', '641', 'charge', 5000.00, NOW(), NOW());

-- Transactions
INSERT INTO transactions (date, description, compte_debit_id, compte_credit_id, montant, created_at, updated_at) VALUES
('2023-01-15', 'Paiement client ABC', 1, 3, 1000.00, NOW(), NOW()),
('2023-01-20', 'Achat de fournitures', 8, 1, 500.00, NOW(), NOW()),
('2023-01-25', 'Paiement loyer', 9, 1, 1200.00, NOW(), NOW()),
('2023-02-01', 'Vente de produits', 3, 6, 2000.00, NOW(), NOW()),
('2023-02-10', 'Paiement salaires', 10, 1, 5000.00, NOW(), NOW()),
('2023-02-15', 'Prestation de service', 3, 7, 1500.00, NOW(), NOW()),
('2023-02-20', 'Remboursement emprunt', 5, 1, 500.00, NOW(), NOW()),
('2023-03-01', 'Paiement fournisseur XYZ', 4, 1, 1000.00, NOW(), NOW()),
('2023-03-10', 'Encaissement client', 1, 3, 2000.00, NOW(), NOW()),
('2023-03-15', 'Achat de matériel', 8, 1, 1500.00, NOW(), NOW());

-- Entrées journal
INSERT INTO entrees_journal (transaction_id, utilisateur_id, created_at, updated_at) VALUES
(1, 2, NOW(), NOW()),
(2, 2, NOW(), NOW()),
(3, 2, NOW(), NOW()),
(4, 2, NOW(), NOW()),
(5, 2, NOW(), NOW()),
(6, 2, NOW(), NOW()),
(7, 2, NOW(), NOW()),
(8, 2, NOW(), NOW()),
(9, 2, NOW(), NOW()),
(10, 2, NOW(), NOW());
