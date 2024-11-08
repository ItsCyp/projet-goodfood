<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\action\Action;

/**
 * Classe PeriodeAction
 * Permet à l'utilisateur de définir ou de mettre à jour une période de dates qui sera stockée dans les cookies.
 */
class PeriodeAction extends Action
{
    /**
     * Exécute l'action de configuration de la période.
     *
     * @return string Le contenu HTML généré pour la configuration de la période.
     */
    public function execute(): string
    {
        // Définit le message d'invite en fonction de l'existence ou non de dates déjà enregistrées.
        $message = '';
        if (isset($_COOKIE['dateDebut']) && isset($_COOKIE['dateFin'])) {
            $message = 'Définir une nouvelle période';
        } else {
            $message = 'Définir la période';
        }

        $html = '';
        if ($this->http_method == 'GET') {
            // Mode GET : affiche le formulaire avec les dates actuelles en valeurs par défaut.
            $dateDebut = $_COOKIE['dateDebut'] ?? '';  // Date de début actuelle ou valeur vide.
            $dateFin = $_COOKIE['dateFin'] ?? '';       // Date de fin actuelle ou valeur vide.
            $html = <<<HTML
                <h2>$message</h2>
                <form method="post" action="?action=setPeriod">
                    <label>Date de début :
                    <input type="date" name="dateDebut" value="$dateDebut" placeholder="jj/mm/aaaa"></label><br>
                    <label>Date de fin :
                    <input type="date" name="dateFin" value="$dateFin" placeholder="jj/mm/aaaa"></label><br>
                    <button type="submit">Enregistrer la période</button>
                </form>
            HTML;
        } elseif ($this->http_method == 'POST') {
            // Mode POST : enregistre les dates dans les cookies après soumission du formulaire.
            $dateDebut = $_POST['dateDebut'];
            $dateFin = $_POST['dateFin'];

            // Définit des cookies pour stocker les dates, avec une durée de vie de 30 jours.
            setcookie('dateDebut', $dateDebut, time() + (86400 * 30), "/");
            setcookie('dateFin', $dateFin, time() + (86400 * 30), "/");

            // Message de confirmation après enregistrement.
            $html = '<p>La période a été enregistrée.</p>';
        }
        return $html;
    }
}
