<?php

namespace Neptunium\Core;

use PDO;

class DatabaseConnection {
    public ?Database $db = null;
    private static ?DatabaseConnection $databaseInstance = null;

    private function __construct() {
        $db_host = $_ENV['DB_HOST'];
        $db_name = $_ENV['DB_NAME'];
        $db_user = $_ENV['DB_USER'];
        $db_pass = $_ENV['DB_PASS'];
        $db_port = $_ENV['DB_PORT'];

        try {
            $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=UTF8;";
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $dbPDO = new \PDO($dsn, $db_user, $db_pass, $opt);
            $this->db = new Database($dbPDO, "", false);
        } catch (\PDOException $exc) {
//            $fe = new FrameworkException("Błąd PDO podczas inicjowania połączenia z bazą danych !!!", $exc->getMessage());
//            $fe->showError();
//            self::$errorMsg = $fe->getErrorMsg();
            echo '<pre>';
            print_r($exc);
            echo '</pre>';
        }
    }

    public static function getConnection(): DatabaseConnection {
        if (!self::$databaseInstance) {
            self::$databaseInstance = new DatabaseConnection();
        }
        return self::$databaseInstance;
    }
}
