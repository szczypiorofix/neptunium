<?php

namespace Neptunium\Core;

use Neptunium\ModelClasses\Database;
use PDO;

class DatabaseConnection {
    public ?Database $db = null;
    private static ?DatabaseConnection $databaseInstance = null;

    private function __construct() {
        $this->db = new Database();
        $databaseHost = $this->db->getHost();
        $databasePort = $this->db->getPort();
        $databaseName = $this->db->getName();
        $databaseUser = $this->db->getUsername();
        $databasePassword = $this->db->getPassword();
        $databaseCharset = $this->db->getCharset();

        try {
            $dsn = "mysql:host=$databaseHost;port=$databasePort;dbname=$databaseName;charset=$databaseCharset;";
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '$databaseCharset'"
            ];
            $dbPDO = new \PDO($dsn, $databaseUser, $databasePassword, $opt);
            $this->db->setDb($dbPDO);
        } catch (\PDOException $exc) {
            $this->db->setErrorMessage($exc->getMessage());
            $this->db->setError(true);
        }
    }

    public static function getConnection(): DatabaseConnection {
        if (!self::$databaseInstance) {
            self::$databaseInstance = new DatabaseConnection();
        }
        return self::$databaseInstance;
    }
}
