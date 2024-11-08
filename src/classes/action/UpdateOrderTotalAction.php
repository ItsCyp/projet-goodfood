<?php

namespace iutnc\goodfood\action;

use iutnc\goodfood\database\GoodfoodDatabase;

/**
 * Classe UpdateOrderTotalAction
 * Permet de mettre à jour le total d'une commande en fonction des plats et de leurs quantités.
 */
class UpdateOrderTotalAction extends Action
{
    /**
     * Exécute l'action de mise à jour du total d'une commande.
     *
     * @return string Le contenu HTML à afficher.
     */
    public function execute(): string
    {
        $html = ''; // Initialisation de la variable HTML qui contiendra le contenu à afficher.

        // Si la requête est de type GET, on affiche la liste des commandes et un formulaire de mise à jour.
        if ($this->http_method == 'GET') {
            // Récupère l'instance de la base de données via le singleton.
            $db = GoodfoodDatabase::getInstance();

            // Récupère les numéros des commandes existantes dans la base de données.
            $orders = $db->getOrderNumbers();

            // Affiche l'entête et la liste des commandes.
            $html = '<h2>Update Order Total</h2>';
            $html .= '<h3>Order Numbers:</h3><ul>';
            foreach ($orders as $order) {
                // Affiche chaque numéro de commande dans une liste HTML.
                $html .= '<li>' . $order['numcom'] . '</li>';
            }
            $html .= '</ul>';

            // Affiche le formulaire permettant de saisir le numéro d'une commande pour mettre à jour son total.
            $html .= <<<HTML
                <form method="post" action="?action=updateOrderTotal">
                    <label>Order Number:
                    <input type="number" name="numCom" placeholder="1" required></label><br>
                    <button type="submit">Update Total</button>
                </form>
            HTML;
        }

        // Si la requête est de type POST, on met à jour le total de la commande spécifiée.
        elseif ($this->http_method == 'POST') {
            // Récupère le numéro de commande envoyé via le formulaire.
            $numCom = !empty($_POST['numCom']) ? (int)$_POST['numCom'] : 0;

            // Vérifie que le numéro de commande est valide (supérieur à 0).
            if ($numCom > 0) {
                // Récupère l'instance de la base de données.
                $db = GoodfoodDatabase::getInstance();

                // Appelle la méthode pour mettre à jour le total de la commande.
                $db->updateOrderTotal($numCom);

                // Affiche un message confirmant que le total a été mis à jour.
                $html = '<p>Order total updated successfully for order number ' . $numCom . '.</p>';
            } else {
                // Si le numéro de commande est invalide, affiche un message d'erreur.
                $html = '<p>Invalid order number.</p>';
            }
        }

        // Retourne le contenu HTML généré (soit le formulaire, soit le message de confirmation).
        return $html;
    }
}
