<?php

namespace iutnc\goodfood\database;

use PDO;

class GoodfoodDatabase
{
    // Instance PDO pour la connexion à la base de données
    private PDO $pdo;

    // Instance unique de GoodfoodDatabase pour l'implémentation du pattern Singleton
    private static ?GoodfoodDatabase $instance = null;

    // Configuration de connexion à la base de données, chargée depuis un fichier de configuration
    private static array $config = [];

    /**
     * Constructeur privé pour empêcher l'instanciation directe de GoodfoodDatabase.
     * Initialise la connexion à la base de données PDO en utilisant les informations de configuration.
     *
     * @param array $conf Tableau associatif contenant 'host', 'dbname', 'user' et 'password'.
     */
    private function __construct(array $conf)
    {
        // DSN (Data Source Name) pour la connexion MySQL
        $dsn = 'mysql:host=' . $conf['host'] . ';dbname=' . $conf['dbname'];
        // Création de l'instance PDO avec le mode d'erreur en exception
        $this->pdo = new PDO($dsn, $conf['user'], $conf['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    /**
     * Charge la configuration depuis un fichier INI et la stocke dans la propriété statique $config.
     *
     * @param string $file Chemin du fichier de configuration INI.
     * @throws \Exception Si le fichier de configuration ne peut pas être lu.
     */
    public static function setConfig(string $file): void
    {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception('Erreur lors de la lecture du fichier de configuration.');
        }

        // Stocke les informations de configuration dans la variable statique $config
        self::$config = [
            'host' => $conf['host'] ?? null,
            'dbname' => $conf['dbname'] ?? null,
            'user' => $conf['user'] ?? null,
            'password' => $conf['password'] ?? null
        ];
    }

    /**
     * Renvoie l'instance unique de GoodfoodDatabase (pattern Singleton).
     * Si aucune instance n'existe, elle est créée en utilisant les informations de configuration.
     *
     * @return GoodfoodDatabase|null L'instance unique de la base de données.
     */
    public static function getInstance(): ?GoodfoodDatabase
    {
        if (is_null(self::$instance)) {
            self::$instance = new GoodfoodDatabase(self::$config);
        }
        return self::$instance;
    }

    /**
     * Récupère la liste des plats commandés dans une période donnée.
     *
     * @param string $dateDebut Date de début au format 'YYYY-MM-DD'.
     * @param string $dateFin Date de fin au format 'YYYY-MM-DD'.
     * @return array Liste des plats commandés avec leur numéro et libellé.
     */
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

    /**
     * Récupère la liste des plats qui n'ont pas été commandés dans une période donnée.
     *
     * @param string $dateDebut Date de début au format 'YYYY-MM-DD'.
     * @param string $dateFin Date de fin au format 'YYYY-MM-DD'.
     * @return array Liste des plats non commandés avec leur numéro et libellé.
     */
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

    /**
     * Récupère la liste des serveurs ayant servi une table donnée dans une période.
     *
     * @param int $numTable Numéro de la table.
     * @param string $dateDebut Date de début.
     * @param string $dateFin Date de fin.
     * @return array Liste des serveurs avec leur nom et date d'affectation.
     */
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

    /**
     * Calcule le chiffre d'affaires et le nombre de commandes par serveur dans une période donnée.
     *
     * @param string $dateDebut Date de début.
     * @param string $dateFin Date de fin.
     * @return array Liste des serveurs avec leur chiffre d'affaires et nombre de commandes.
     */
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

    /**
     * Récupère la liste des serveurs sans chiffre d'affaires dans une période donnée.
     *
     * @param string $dateDebut Date de début.
     * @param string $dateFin Date de fin.
     * @return array Liste des serveurs sans chiffre d'affaires.
     */
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

    /**
     * Met à jour le total d'une commande en recalculant le montant total.
     *
     * @param int $numCom Numéro de la commande à mettre à jour.
     */
    public function updateOrderTotal(int $numCom): void
    {
        // Calcul du total de la commande en multipliant les prix unitaires par les quantités
        $sql = 'SELECT SUM(p.prixunit * c.quantite) AS total
                FROM PLAT p
                JOIN CONTIENT c ON p.numPlat = c.numPlat
                WHERE c.numCom = :numCom';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['numCom' => $numCom]);
        $total = $stmt->fetchColumn();

        // Mise à jour du montant total de la commande dans la table COMMANDE
        $updateSql = 'UPDATE COMMANDE SET montcom = :total WHERE numcom = :numCom';
        $updateStmt = $this->pdo->prepare($updateSql);
        $updateStmt->execute(['total' => $total, 'numCom' => $numCom]);
    }

    /**
     * Récupère toutes les commandes.
     *
     * @return array Liste des commandes.
     */
    public function getOrderNumbers(): array
    {
        $sql = 'SELECT * FROM COMMANDE';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
