<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

/**
 * Classe NoRevenueServersAction
 * Représente l'action d'afficher les serveurs n'ayant pas généré de chiffre d'affaires pendant une période donnée.
 */
class NoRevenueServersAction extends Action
{
    /**
     * Exécute l'action pour afficher la liste des serveurs sans chiffre d'affaires.
     *
     * @return string Le contenu HTML généré pour l'affichage des serveurs sans chiffre d'affaires.
     */
    public function execute(): string
    {
        // Vérifie si les dates de début et de fin sont définies dans les cookies.
        if (empty($_COOKIE['dateDebut']) || empty($_COOKIE['dateFin'])) {
            // Redirige vers la page pour définir la période si les dates sont absentes.
            header('Location: ?action=setPeriod');
            exit();
        }

        // Récupère les dates de début et de fin de la période à partir des cookies.
        $dateDebut = $_COOKIE['dateDebut'];
        $dateFin = $_COOKIE['dateFin'];

        // Initialisation du HTML pour afficher le titre de la section.
        $html = '<h1>Serveurs n\'ayant pas généré de chiffre d\'affaires</h1>';
        $html .= '<ul>';

        // Récupère une instance de la base de données et appelle la méthode pour obtenir les serveurs sans chiffre d'affaires.
        $db = GoodfoodDatabase::getInstance();
        $serveurs = $db->getServeursSansChiffreAffaire($dateDebut, $dateFin);

        // Parcourt chaque serveur pour ajouter un élément de liste HTML avec son nom.
        foreach ($serveurs as $serveur) {
            $html .= '<li>' . htmlspecialchars($serveur['nomserv']) . '</li>';
        }
        $html .= '</ul>';

        // Retourne le contenu HTML généré.
        return $html;
    }
}
