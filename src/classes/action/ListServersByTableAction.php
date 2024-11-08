<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

/**
 * Classe ListServersByTableAction
 * Représente l'action d'afficher les serveurs affectés à une table spécifique durant une période donnée.
 */
class ListServersByTableAction extends Action
{
    /**
     * Exécute l'action d'affichage des serveurs par table.
     *
     * @return string Le contenu HTML généré pour l'affichage des serveurs par table.
     */
    public function execute(): string
    {
        // Vérifie si les dates de début et de fin sont définies dans les cookies.
        if (empty($_COOKIE['dateDebut']) || empty($_COOKIE['dateFin'])) {
            // Redirige vers la page pour définir la période si les dates sont absentes.
            header('Location: ?action=setPeriod');
            exit();
        }

        // Initialisation de la variable qui contiendra le code HTML à retourner.
        $html = '';

        // Vérifie la méthode HTTP utilisée pour déterminer l'action appropriée.
        if ($this->http_method == 'GET') {
            // Si la requête est en GET, affiche un formulaire pour saisir le numéro de la table.
            $html = <<<HTML
                <h2>Serveurs par table</h2>
                <form method="post" action="?action=listServersByTable">
                    <label>Numéro de table :
                    <input type="number" name="numTable" placeholder="1"></label><br>
                    <button type="submit">Afficher les serveurs par table</button>
                </form>
            HTML;
        } elseif ($this->http_method == 'POST') {
            // Si la requête est en POST, récupère le numéro de table saisi par l'utilisateur.
            // Définit 1 comme numéro de table par défaut si aucune valeur n'est fournie.
            $numTable = !empty($_POST['numTable']) ? $_POST['numTable'] : 1;
            $dateDebut = $_COOKIE['dateDebut'];
            $dateFin = $_COOKIE['dateFin'];

            // Crée l'en-tête HTML pour afficher les serveurs affectés à la table donnée.
            $html = '<h1>Serveurs de la table ' . htmlspecialchars($numTable) . '</h1>';
            $html .= '<ul>';

            // Récupère une instance de la base de données et les serveurs affectés à cette table.
            $db = GoodfoodDatabase::getInstance();
            $serveurs = $db->getServeursParTable($numTable, $dateDebut, $dateFin);

            // Parcourt chaque serveur pour ajouter un élément de liste HTML avec son numéro et son nom.
            foreach ($serveurs as $serveur) {
                $html .= '<li>' . htmlspecialchars($serveur['numServeur']) . ', ' . htmlspecialchars($serveur['nom']) . '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
}
