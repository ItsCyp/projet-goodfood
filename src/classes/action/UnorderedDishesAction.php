<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

class UnorderedDishesAction extends Action
{
    public function execute(): string
    {
        $html = '';
        if ($this->http_method == 'GET') {
            $html = <<<HTML
                <h2>Plats non commandés</h2>
                <form method="post" action="?action=unorderedDishes">
                    <label>Date de début :
                    <input type="date" name="dateDebut" placeholder="jj/mm/aaaa"><label><br>
                    <label>Date de fin :
                    <input type="date" name="dateFin" placeholder="jj/mm/aaaa"><label><br>
                    <button type="submit">Afficher les plats non commandés</button>
                </form>
            HTML;
        } elseif ($this->http_method == 'POST') {
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            $html = '<h1>Liste des plats non commandés</h1>';
            $html .= '<ul>';
            $db = GoodfoodDatabase::getInstance();
            $plats = $db->getPlatsNonCommandes($dateDebut, $dateFin);
            foreach ($plats as $plat) {
                $html .= '<li>' . $plat['numPlat'] . ', ' . $plat['libelle'] . '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
        return $html;
    }
}