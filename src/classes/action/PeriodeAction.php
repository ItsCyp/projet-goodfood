<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\action\Action;

class PeriodeAction extends Action
{

    public function execute(): string
    {
        $message = '';
        if(isset($_COOKIE['dateDebut']) && isset($_COOKIE['dateFin'])) {
            $message = 'Définir une nouvelle période';
        }else{
            $message = 'Définir la période';
        }

        $html = '';
        if($this->http_method == 'GET') {
            $dateDebut = $_COOKIE['dateDebut'] ?? '';
            $dateFin = $_COOKIE['dateFin'] ?? '';
            $html = <<<HTML
                <h2>$message</h2>
                <form method="post" action="?action=setPeriod">
                    <label>Date de début :
                    <input type="date" name="dateDebut" value="$dateDebut" placeholder="jj/mm/aaaa"><label><br>
                    <label>Date de fin :
                    <input type="date" name="dateFin" value="$dateFin" placeholder="jj/mm/aaaa"><label><br>
                    <button type="submit">Enregistrer la période</button>
                </form>
            HTML;
        } elseif($this->http_method == 'POST') {
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];
            setcookie('dateDebut', $dateDebut, time() + (86400 * 30), "/"); // 30 jours
            setcookie('dateFin', $dateFin, time() + (86400 * 30), "/"); // 30 jours
            $html = '<p>La période a été enregistrée.</p>';
        }
        return $html;
    }
}