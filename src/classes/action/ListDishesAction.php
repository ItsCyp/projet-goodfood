<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

class ListDishesAction extends Action
{
    public function execute(): string
    {
        $html = '';
        if ($this->http_method == 'GET') {
            $html = <<<HTML
                <h2>Plats servis</h2>
                <form method="post" action="?action=listDishes">
                    <label>Date de début :
                    <input type="date" name="dateDebut" placeholder="jj/mm/aaaa"><label><br>
                    <label>Date de fin :
                    <input type="date" name="dateFin" placeholder="jj/mm/aaaa"><label><br>
                    <button type="submit">Afficher les plats servis</button>
                </form>
            HTML;
        } elseif ($this->http_method == 'POST') {
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            $html = '<h1>Liste des plats</h1>';
            $html .= '<ul>';
            $db = GoodfoodDatabase::getInstance();
            $plats = $db->getPlatsServis($dateDebut, $dateFin);
            foreach ($plats as $plat) {
                $html .= '<li>' . $plat['numPlat'] . ', ' . $plat['libelle'] . '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
        return $html;
    }
}
