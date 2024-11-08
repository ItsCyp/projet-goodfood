<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

class ListServersByTableAction extends Action
{
    public function execute(): string
    {
        if (empty($_COOKIE['dateDebut']) || empty($_COOKIE['dateFin'])) {
            header('Location: ?action=setPeriod');
            exit();
        }

        $html = '';
        if ($this->http_method == 'GET') {
            $html = <<<HTML
                <h2>Serveurs par table</h2>
                <form method="post" action="?action=listServersByTable">
                    <label>Numéro de table :
                    <input type="number" name="numTable" placeholder="1"></label><br>
                    <button type="submit">Afficher les serveurs par table</button>
                </form>
            HTML;
        } elseif ($this->http_method == 'POST') {
            $numTable = !empty($_POST['numTable']) ? $_POST['numTable'] : 1;
            $dateDebut = $_COOKIE['dateDebut'];
            $dateFin = $_COOKIE['dateFin'];
            $html = '<h1>Serveurs de la table ' . $numTable . '</h1>';
            $html .= '<ul>';
            $db = GoodfoodDatabase::getInstance();
            $serveurs = $db->getServeursParTable($numTable, $dateDebut, $dateFin);
            foreach ($serveurs as $serveur) {
                $html .= '<li>' . $serveur['numServeur'] . ', ' . $serveur['nom'] . '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
        return $html;
    }
}