<?php

namespace iutnc\goodfood\dispatch;

use iutnc\goodfood\action as act;

class Dispatcher
{
    private ?string $action = null;

    /**
     * Constructeur de la classe Dispatcher.
     * Initialise l'action à partir des paramètres GET.
     * Si un paramètre 'action' est présent dans l'URL, il est récupéré et sécurisé
     * grâce à `filter_input` pour éviter les injections.
     * Si aucun paramètre n'est fourni, l'action par défaut est fixée à 'default'.
     */
    function __construct()
    {
        $this->action = isset($_GET['action']) ? filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) : 'default';
    }

    /**
     * Méthode principale qui exécute l'action en fonction de la valeur de $action.
     * Pour chaque valeur spécifique de $action, une nouvelle instance de l'action appropriée
     * est créée, puis la méthode `execute` de cette action est appelée pour générer le contenu HTML.
     * Le contenu HTML est ensuite stocké dans la variable $html pour être rendu plus tard.
     */
    public function run(): void
    {
        // Variable qui va stocker le contenu HTML généré par l'action exécutée.
        $html = '';

        // Switch qui détermine quelle action exécuter en fonction de la valeur de $this->action.
        switch ($this->action) {
            case 'default':
                // Action par défaut - accueil de l'application.
                $action = new act\DefaultAction();
                $html = $action->execute();
                break;
            case 'listDishes':
                // Liste les plats disponibles.
                $action = new act\ListDishesAction();
                $html = $action->execute();
                break;
            case 'unorderedDishes':
                // Liste les plats qui n'ont jamais été commandés.
                $action = new act\UnorderedDishesAction();
                $html = $action->execute();
                break;
            case 'listServersByTable':
                // Liste les serveurs affectés à une table spécifique pendant une période.
                $action = new act\ListServersByTableAction();
                $html = $action->execute();
                break;
            case 'serverRevenue':
                // Affiche le chiffre d'affaires et le nombre de commandes pour chaque serveur.
                $action = new act\ServerRevenueAction();
                $html = $action->execute();
                break;
            case 'noRevenueServers':
                // Liste les serveurs n'ayant réalisé aucun chiffre d'affaires pendant une période donnée.
                $action = new act\NoRevenueServersAction();
                $html = $action->execute();
                break;
            case 'updateOrderTotal':
                // Calcule le total d'une commande donnée et met à jour la base de données.
                $action = new act\UpdateOrderTotalAction();
                $html = $action->execute();
                break;
            case 'setPeriod':
                // Permet à l'utilisateur de définir une période de temps.
                $action = new act\PeriodeAction();
                $html = $action->execute();
                break;
        }
        // Appel de la méthode renderPage pour afficher la page HTML avec le contenu généré.
        $this->renderPage($html);
    }

    /**
     * Affiche la page HTML avec le contenu généré par l'action.
     * La page inclut une barre de navigation pour accéder aux différentes actions.
     *
     * @param string $html Le contenu HTML à afficher dans le corps de la page.
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
    <li><a href="?action=setPeriod">Définir la période</a></li>
</ul>
    <div class="container">
        $html
    </div>
</body>
</html>
HTML;
    }
}