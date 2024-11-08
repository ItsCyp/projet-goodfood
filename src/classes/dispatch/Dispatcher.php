<?php

namespace iutnc\goodfood\dispatch;

use iutnc\goodfood\action as act;

class Dispatcher
{
    private ?string $action = null;

    /**
     * Constructeur de la classe Dispatcher.
     * Initialise l'action à partir des paramètres GET.
     */
    function __construct()
    {
        $this->action = isset($_GET['action']) ? filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) : 'default';
    }

    /**
     * Exécute l'action en fonction de la valeur de $action.
     */
    public function run(): void
    {
        switch ($this->action) {
            case 'default':
                $action = new act\DefaultAction();
                $html = $action->execute();
                break;
            case 'listDishes':
                $action = new act\ListDishesAction();
                $html = $action->execute();
                break;
            case 'unorderedDishes':
                $action = new act\UnorderedDishesAction();
                $html = $action->execute();
                break;
            case 'listServersByTable':
                $action = new act\ListServersByTableAction();
                $html = $action->execute();
                break;
            case 'serverRevenue':
                $action = new act\ServerRevenueAction();
                $html = $action->execute();
                break;
            case 'noRevenueServers':
                $action = new act\NoRevenueServersAction();
                $html = $action->execute();
                break;
            case 'updateOrderTotal':
                $action = new act\UpdateOrderTotalAction();
                $html = $action->execute();
                break;
        }
        $this->renderPage($html);
    }

    /**
     * Affiche la page HTML avec le contenu généré par l'action.
     *
     * @param string $html Le contenu HTML à afficher.
     */
    private function renderPage(string $html): void
    {
        echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>GoodFood</title>
</head>
<body>
    <h1>GoodFood</h1>
    <ul class="nav-links">
    <li><a href="?action=default">Accueil</a></li>
    <li><a href="?action=listDishes">Liste des plats</a></li>
    <li><a href="?action=unorderedDishes">Plats non commandés</a></li>
    <li><a href="?action=listServersByTable">Serveurs par table</a></li>
    <li><a href="?action=serverRevenue">Chiffre d'affaire des serveurs</a></li>
    <li><a href="?action=noRevenueServers">Serveurs sans chiffre d'affaire</a></li>
    <li><a href="?action=updateOrderTotal">Mettre à jour le total de la commande</a></li>
</ul>
    <div class="container">
        $html
    </div>
</body>
</html>
HTML;
    }
}