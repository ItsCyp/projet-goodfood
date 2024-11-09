<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\repository\GoodfoodRepository;

/**
 * Classe UnorderedDishesAction
 * Affiche la liste des plats qui n'ont pas été commandés durant une période donnée.
 */
class UnorderedDishesAction extends Action
{
    /**
     * Exécute l'action de récupération et d'affichage des plats non commandés.
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
        $html = '<h1>Plats non commandés</h1>';
        $html .= '<ul>'; // Début de la liste des plats non commandés.

        // Récupère l'instance de la base de données via le singleton.
        $db = GoodfoodRepository::getInstance();

        // Récupère les plats non commandés durant la période spécifiée.
        $plats = $db->getPlatsNonCommandes($dateDebut, $dateFin);

        // Parcourt les résultats des plats non commandés et génère le HTML pour chaque plat.
        foreach ($plats as $plat) {
            // Ajoute chaque plat à la liste avec son numéro et son libellé.
            $html .= '<li>' . $plat['numPlat'] . ', ' . $plat['libelle'] . '</li>';
        }

        $html .= '</ul>'; // Fin de la liste des plats non commandés.

        return $html; // Retourne le contenu HTML généré.
    }
}
