<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

iutnc\goodfood\repository\GoodfoodRepository::setConfig('config.db.ini');

$d = new \iutnc\goodfood\dispatch\Dispatcher();
$d->run();