<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

class ListServersByTableAction extends Action
{
    public function execute(): string
    {
        $html = '';
        if ($this->http_method == 'GET') {
            $html = <<<HTML
                <h2>Serveurs par table</h2>
                <form method="post" action="?action=listServersByTable">
                    <label>Numéro de table :
                    <input type="number" name="numTable" placeholder="1"></label><br>
                    <label>Date de début :
                    <input type="date" name="dateDebut" placeholder="jj/mm/aaaa"><label><br>
                    <label>Date de fin :
                    <input type="date" name="dateFin" placeholder="jj/mm/aaaa"><label><br>
                    <button type="submit">Afficher les serveurs par table</button>
                </form>
            HTML;
        } elseif ($this->http_method == 'POST') {
            $numTable = $_POST['numTable'];
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            $html = '<h1>Liste des serveurs par table</h1>';
            $html .= '<ul>';
            $db = GoodfoodDatabase::getInstance();
            $serveurs = $db->getServeursParTable($numTable, $dateDebut, $dateFin);
            foreach ($serveurs as $serveur) {
                $html .= '<li>' . $serveur['numServeur'] . ', ' . $serveur['nom'] . ', ' . $serveur['prenom'] . ', ' . $serveur['numTable'] . '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
        return $html;
    }
}