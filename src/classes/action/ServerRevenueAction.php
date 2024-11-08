<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

class ServerRevenueAction extends Action
{

    public function execute(): string
    {
        if (empty($_COOKIE['dateDebut']) || empty($_COOKIE['dateFin'])) {
            header('Location: ?action=setPeriod');
            exit();
        }

        $dateDebut = $_COOKIE['dateDebut'];
        $dateFin = $_COOKIE['dateFin'];
        $html = '<h1>Chiffre d\'affaires et nombre de commandes par serveur</h1>';
        $html .= '<ul>';
        $db = GoodfoodDatabase::getInstance();
        $serveurs = $db->getChiffreAffaireParServeur($dateDebut, $dateFin);
        foreach ($serveurs as $serveur) {
            $html .= '<li>' . $serveur['nomserv'] . ': ' . $serveur['chiffreAffaire'] . '€, ' . $serveur['nbCommandes'] . ' commandes</li>';
        }
        $html .= '</ul>';
        return $html;
    }
}