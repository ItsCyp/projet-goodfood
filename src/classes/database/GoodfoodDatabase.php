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
        if ($conf === false) {
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

    public function getPlatsServis(string $dateDebut, string $dateFin): array
    {
        $sql = "SELECT DISTINCT p.numPlat, p.libelle 
        FROM COMMANDE c
        JOIN CONTIENT co ON c.numcom = co.numcom
        JOIN PLAT p ON co.numplat = p.numplat 
        WHERE c.datcom BETWEEN STR_TO_DATE(:dateDebut, '%Y-%m-%d') AND STR_TO_DATE(:dateFin, '%Y-%m-%d');";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['dateDebut' => $dateDebut, 'dateFin' => $dateFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPlatsNonCommandes(string $dateDebut, string $dateFin): array
    {
        $sql = "SELECT p.numPlat, p.libelle 
            FROM PLAT p
            WHERE p.numPlat NOT IN (
                SELECT DISTINCT c.numPlat 
                FROM CONTIENT c
                JOIN COMMANDE com ON c.numCom = com.numCom
                WHERE com.datcom BETWEEN STR_TO_DATE(:dateDebut, '%Y-%m-%d') AND STR_TO_DATE(:dateFin, '%Y-%m-%d')
            );";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['dateDebut' => $dateDebut, 'dateFin' => $dateFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServeursParTable(int $numTable, string $dateDebut, string $dateFin): array
    {
        $sql = "SELECT s.nomserv, a.dataff 
            FROM SERVEUR s
            JOIN AFFECTER a ON s.numserv = a.numserv
            WHERE a.numtab = :numTable AND a.dataff BETWEEN STR_TO_DATE(:dateDebut, '%Y-%m-%d') AND STR_TO_DATE(:dateFin, '%Y-%m-%d')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['numTable' => $numTable, 'dateDebut' => $dateDebut, 'dateFin' => $dateFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChiffreAffaireParServeur(string $dateDebut, string $dateFin): array
    {
        $sql = "SELECT s.nomserv, SUM(com.montcom) AS chiffreAffaire, COUNT(com.numcom) AS nbCommandes
            FROM SERVEUR s
            JOIN AFFECTER a ON s.numserv = a.numserv
            JOIN COMMANDE com ON a.numtab = com.numtab AND a.dataff = com.datcom
            WHERE com.datcom BETWEEN STR_TO_DATE(:dateDebut, '%Y-%m-%d') AND STR_TO_DATE(:dateFin, '%Y-%m-%d')
            GROUP BY s.nomserv
            ORDER BY chiffreAffaire DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['dateDebut' => $dateDebut, 'dateFin' => $dateFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServeursSansChiffreAffaire(string $dateDebut, string $dateFin): array
    {
        $sql = "SELECT s.nomserv
            FROM SERVEUR s
            WHERE s.numserv NOT IN (
                SELECT DISTINCT a.numserv
                FROM AFFECTER a
                JOIN COMMANDE com ON a.numtab = com.numtab AND a.dataff = com.datcom
                WHERE com.datcom BETWEEN STR_TO_DATE(:dateDebut, '%Y-%m-%d') AND STR_TO_DATE(:dateFin, '%Y-%m-%d')
            )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['dateDebut' => $dateDebut, 'dateFin' => $dateFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrderTotal(int $numCom): void
    {
        $sql = 'SELECT SUM(p.prixunit * c.quantite) AS total
            FROM PLAT p
            JOIN CONTIENT c ON p.numPlat = c.numPlat
            WHERE c.numCom = :numCom';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['numCom' => $numCom]);
        $total = $stmt->fetchColumn();

        $updateSql = 'UPDATE COMMANDE SET montcom = :total WHERE numcom = :numCom';
        $updateStmt = $this->pdo->prepare($updateSql);
        $updateStmt->execute(['total' => $total, 'numCom' => $numCom]);
    }

    public function getOrderNumbers(): array
    {
        $sql = 'SELECT * FROM COMMANDE';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}