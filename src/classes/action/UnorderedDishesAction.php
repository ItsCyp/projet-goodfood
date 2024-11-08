<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

class UnorderedDishesAction extends Action
{
    public function execute(): string
    {
        if (empty($_COOKIE['dateDebut']) || empty($_COOKIE['dateFin'])) {
            header('Location: ?action=setPeriod');
            exit();
        }

        $dateDebut = $_COOKIE['dateDebut'];
        $dateFin = $_COOKIE['dateFin'];
        $html = '<h1>Plats non commandés</h1>';
        $html .= '<ul>';
        $db = GoodfoodDatabase::getInstance();
        $plats = $db->getPlatsNonCommandes($dateDebut, $dateFin);
        foreach ($plats as $plat) {
            $html .= '<li>' . $plat['numPlat'] . ', ' . $plat['libelle'] . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}