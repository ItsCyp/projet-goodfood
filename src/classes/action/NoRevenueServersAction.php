<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

class NoRevenueServersAction extends Action
{
    public function execute(): string
    {
        if (empty($_COOKIE['dateDebut']) || empty($_COOKIE['dateFin'])) {
            header('Location: ?action=setPeriod');
            exit();
        }

        $dateDebut = $_COOKIE['dateDebut'];
        $dateFin = $_COOKIE['dateFin'];
        $html = '<h1>Serveurs n\'ayant pas généré de chiffre d\'affaires</h1>';
        $html .= '<ul>';
        $db = GoodfoodDatabase::getInstance();
        $serveurs = $db->getServeursSansChiffreAffaire($dateDebut, $dateFin);
        foreach ($serveurs as $serveur) {
            $html .= '<li>' . $serveur['nomserv'] . '</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}