<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\repository\GoodfoodRepository;

/**
 * Classe ListDishesAction
 * Représente l'action d'afficher la liste des plats servis dans une période spécifiée.
 */
class ListDishesAction extends Action
{
    /**
     * Exécute l'action d'affichage de la liste des plats.
     *
     * @return string Le contenu HTML de la liste des plats servis dans la période donnée.
     */
    public function execute(): string
    {
        // Vérifie si les dates de début et de fin sont définies dans les cookies
        if (empty($_COOKIE['dateDebut']) || empty($_COOKIE['dateFin'])) {
            // Redirige vers la page pour définir la période si les dates sont absentes
            header('Location: ?action=setPeriod');
            exit();
        }

        // Récupère les dates de début et de fin depuis les cookies
        $dateDebut = $_COOKIE['dateDebut'];
        $dateFin = $_COOKIE['dateFin'];

        // Initialise le contenu HTML avec un en-tête et une liste non ordonnée
        $html = '<h1>Liste des plats</h1>';
        $html .= '<ul>';

        // Récupère une instance de la base de données via le pattern Singleton
        $db = GoodfoodRepository::getInstance();

        // Récupère la liste des plats servis dans la période spécifiée
        $plats = $db->getPlatsServis($dateDebut, $dateFin);

        // Parcourt chaque plat et ajoute un élément de liste HTML pour chacun
        foreach ($plats as $plat) {
            $html .= '<li>' . htmlspecialchars($plat['numPlat']) . ', ' . htmlspecialchars($plat['libelle']) . '</li>';
        }

        // Ferme la liste et renvoie le contenu HTML complet
        $html .= '</ul>';
        return $html;
    }
}
