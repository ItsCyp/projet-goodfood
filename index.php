<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

iutnc\goodfood\database\GoodfoodDatabase::setConfig('config.db.ini');

$d = new \iutnc\goodfood\dispatch\Dispatcher();
$d->run();