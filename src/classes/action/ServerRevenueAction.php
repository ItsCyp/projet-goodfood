<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

/**
 * Classe ServerRevenueAction
 * Affiche le chiffre d'affaires et le nombre de commandes par serveur pour une période donnée.
 */
class ServerRevenueAction extends Action
{
    /**
     * Exécute l'action de récupération et d'affichage du chiffre d'affaires et du nombre de commandes par serveur.
     *
     * @return string Le contenu HTML à afficher.
     */
    public function execute(): string
    {
        // Vérifie si les cookies 'dateDebut' et 'dateFin' sont définis (période sélectionnée par l'utilisateur).
        // Si ces cookies ne sont pas présents, redirige l'utilisateur vers la page de définition de la période.
        if (empty($_COOKIE['dateDebut']) || empty($_COOKIE['dateFin'])) {
            header('Location: ?action=setPeriod');
            exit();
        }

        // Récupère les dates de début et de fin de la période à partir des cookies.
        $dateDebut = $_COOKIE['dateDebut'];
        $dateFin = $_COOKIE['dateFin'];

        // Prépare l'entête du contenu HTML pour la page.
        $html = '<h1>Chiffre d\'affaires et nombre de commandes par serveur</h1>';
        $html .= '<ul>'; // Début de la liste des serveurs.

        // Récupère l'instance de la base de données via le singleton.
        $db = GoodfoodDatabase::getInstance();

        // Récupère le chiffre d'affaires et le nombre de commandes par serveur dans la période spécifiée.
        $serveurs = $db->getChiffreAffaireParServeur($dateDebut, $dateFin);

        // Parcourt les résultats des serveurs et génère le HTML pour chaque serveur.
        foreach ($serveurs as $serveur) {
            // Ajoute chaque serveur à la liste avec son chiffre d'affaires et le nombre de commandes.
            $html .= '<li>' . $serveur['nomserv'] . ': ' . $serveur['chiffreAffaire'] . '€, ' . $serveur['nbCommandes'] . ' commandes</li>';
        }

        $html .= '</ul>'; // Fin de la liste des serveurs.

        return $html; // Retourne le contenu HTML généré.
    }
}
