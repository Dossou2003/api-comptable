<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use SplTempFileObject;

class BalanceController extends Controller
{
    /**
     * Générer un fichier Excel avec la balance comptable.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportExcel()
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

        // Générer le contenu CSV
        $csvContent = $csv->toString();

        // Créer un fichier HTML qui convertira le CSV en Excel côté client
        $htmlContent = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Balance Comptable</title>
            <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #333; }
                table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
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
            </table>

            <script>
                // Données CSV
                const csvData = `' . $csvContent . '`;

                // Fonction pour parser le CSV
                function parseCSV(csv) {
                    const lines = csv.split("\\n");
                    const result = [];
                    const headers = lines[0].split(",");

                    for (let i = 1; i < lines.length; i++) {
                        if (lines[i].trim() === "") continue;
                        const obj = {};
                        const currentLine = lines[i].split(",");

                        for (let j = 0; j < headers.length; j++) {
                            obj[headers[j]] = currentLine[j];
                        }

                        result.push(obj);
                    }

                    return result;
                }

                // Fonction pour remplir le tableau
                function fillTable() {
                    const data = parseCSV(csvData);
                    const tbody = document.querySelector("#balanceTable tbody");

                    data.forEach(row => {
                        const tr = document.createElement("tr");

                        tr.innerHTML = `
                            <td>${row["Code Compte"]}</td>
                            <td>${row["Nom Compte"]}</td>
                            <td>${row["Débit"]}</td>
                            <td>${row["Crédit"]}</td>
                            <td>${row["Solde Final"]}</td>
                        `;

                        tbody.appendChild(tr);
                    });
                }

                // Fonction pour exporter en Excel
                function exportToExcel() {
                    const data = parseCSV(csvData);
                    const worksheet = XLSX.utils.json_to_sheet(data);
                    const workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, worksheet, "Balance Comptable");

                    // Générer le fichier Excel
                    XLSX.writeFile(workbook, "balance_comptable_' . date('Y-m-d') . '.xlsx");
                }

                // Remplir le tableau au chargement de la page
                fillTable();
            </script>
        </body>
        </html>
        ';

        // Enregistrer le fichier HTML
        $filename = 'balance_comptable_' . date('Y-m-d') . '.html';
        Storage::put('public/' . $filename, $htmlContent);
        $path = storage_path('app/public/' . $filename);

        // Retourner le fichier HTML qui permettra de télécharger l'Excel
        return response()->file($path, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
