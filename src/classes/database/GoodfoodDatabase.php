<?php

namespace iutnc\goodfood\database;

use PDO;

class GoodfoodDatabase
{
    private PDO $pdo;
    private static ?GoodfoodDatabase $instance = null;
    private static array $config = [];

    private function __construct(array $conf)
    {
        $dsn = 'mysql:host=' . $conf['host'] . ';dbname=' . $conf['dbname'];
        $this->pdo = new PDO($dsn, $conf['user'], $conf['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public static function setConfig(string $file): void
    {
        $conf = parse_ini_file($file);
        if($conf === false) {
            throw new \Exception('Erreur lors de la lecture du fichier de configuration.');
        }

        self::$config = [
            'host' => $conf['host'] ?? null,
            'dbname' => $conf['dbname'] ?? null,
            'user' => $conf['user'] ?? null,
            'password' => $conf['password'] ?? null
        ];
    }

    public static function getInstance(): ?GoodfoodDatabase
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodfoodDatabase(self::$config);
        }
        return self::$instance;
    }

//    public function getCommande(): array
//    {
//        $sql = 'SELECT * FROM commande';
//        $stmt = $this->pdo->query($sql);
//        return $stmt->fetchAll();
//    }
}