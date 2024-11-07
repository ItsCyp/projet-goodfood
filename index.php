<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

iutnc\goodfood\database\GoodfoodDatabase::setConfig('config.db.ini');

//$db = iutnc\goodfood\database\GoodfoodDatabase::getInstance();
//if ($db === null) {
//    echo "Impossible de se connecter à la base de données.";
//    exit(1);
//}
//var_dump($db->getCommande());