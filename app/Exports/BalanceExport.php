<?php

namespace App\Exports;

use App\Models\Compte;
use League\Csv\Writer;
use SplTempFileObject;

class BalanceExport
{
    /**
     * Exporter la balance comptable au format CSV.
     *
     * @return string Le contenu du fichier CSV
     */
    public function toCsv()
    {
        // Récupérer tous les comptes
        $comptes = Compte::all();

        // Créer un fichier CSV temporaire
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        // Définir l'en-tête
        $csv->insertOne(['Code Compte', 'Nom Compte', 'Débit', 'Crédit', 'Solde Final']);

        // Ajouter les données
        foreach ($comptes as $compte) {
            // Calculer le total des débits et crédits
            $totalDebit = $compte->transactionsDebit()->sum('montant');
            $totalCredit = $compte->transactionsCredit()->sum('montant');

            $csv->insertOne([
                $compte->code,
                $compte->nom,
                number_format($totalDebit, 2, '.', ''),
                number_format($totalCredit, 2, '.', ''),
                number_format($compte->solde, 2, '.', '')
            ]);
        }

        // Retourner le contenu CSV
        return $csv->toString();
    }

    /**
     * Générer un fichier HTML qui peut être téléchargé en Excel.
     *
     * @return string Le contenu du fichier HTML
     */
    public function toHtml()
    {
        // Récupérer tous les comptes
        $comptes = Compte::all();

        // Préparer les données pour le tableau
        $data = [];
        $totalDebit = 0;
        $totalCredit = 0;
        $totalSolde = 0;

        foreach ($comptes as $compte) {
            $debit = $compte->transactionsDebit()->sum('montant');
            $credit = $compte->transactionsCredit()->sum('montant');
            
            $totalDebit += $debit;
            $totalCredit += $credit;
            $totalSolde += $compte->solde;

            $data[] = [
                'code' => $compte->code,
                'nom' => $compte->nom,
                'debit' => number_format($debit, 2, '.', ' '),
                'credit' => number_format($credit, 2, '.', ' '),
                'solde' => number_format($compte->solde, 2, '.', ' ')
            ];
        }

        // Convertir les données en JSON pour le JavaScript
        $jsonData = json_encode($data);

        // Générer le contenu HTML
        $htmlContent = '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Balance Comptable</title>
            <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                h1 {
                    color: #333;
                    text-align: center;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: right;
                }
                th {
                    background-color: #f2f2f2;
                    text-align: center;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                tr:hover {
                    background-color: #f1f1f1;
                }
                .button {
                    background-color: #4CAF50;
                    border: none;
                    color: white;
                    padding: 10px 20px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 10px 2px;
                    cursor: pointer;
                    border-radius: 4px;
                }
                .total-row {
                    font-weight: bold;
                    background-color: #e6e6e6;
                }
                .code-column, .name-column {
                    text-align: left;
                }
            </style>
        </head>
        <body>
            <h1>Balance Comptable</h1>
            <button class="button" onclick="exportToExcel()">Télécharger en Excel</button>

            <table id="balanceTable">
                <thead>
                    <tr>
                        <th>Code Compte</th>
                        <th>Nom Compte</th>
                        <th>Débit</th>
                        <th>Crédit</th>
                        <th>Solde Final</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2">Total</td>
                        <td>' . number_format($totalDebit, 2, '.', ' ') . '</td>
                        <td>' . number_format($totalCredit, 2, '.', ' ') . '</td>
                        <td>' . number_format($totalSolde, 2, '.', ' ') . '</td>
                    </tr>
                </tfoot>
            </table>

            <script>
                // Données de la balance
                const balanceData = ' . $jsonData . ';

                // Fonction pour remplir le tableau
                function fillTable() {
                    const tbody = document.querySelector("#balanceTable tbody");
                    
                    balanceData.forEach(item => {
                        const row = document.createElement("tr");
                        
                        const codeCell = document.createElement("td");
                        codeCell.className = "code-column";
                        codeCell.textContent = item.code;
                        row.appendChild(codeCell);
                        
                        const nameCell = document.createElement("td");
                        nameCell.className = "name-column";
                        nameCell.textContent = item.nom;
                        row.appendChild(nameCell);
                        
                        const debitCell = document.createElement("td");
                        debitCell.textContent = item.debit;
                        row.appendChild(debitCell);
                        
                        const creditCell = document.createElement("td");
                        creditCell.textContent = item.credit;
                        row.appendChild(creditCell);
                        
                        const balanceCell = document.createElement("td");
                        balanceCell.textContent = item.solde;
                        row.appendChild(balanceCell);
                        
                        tbody.appendChild(row);
                    });
                }

                // Fonction pour exporter en Excel
                function exportToExcel() {
                    const table = document.getElementById("balanceTable");
                    const wb = XLSX.utils.table_to_book(table, {sheet: "Balance Comptable"});
                    
                    // Générer le fichier Excel
                    XLSX.writeFile(wb, "balance_comptable_' . date('Y-m-d') . '.xlsx");
                }

                // Remplir le tableau au chargement de la page
                fillTable();
            </script>
        </body>
        </html>
        ';

        return $htmlContent;
    }
}
