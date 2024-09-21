<?php

namespace Neptunium\Core\ORM\Models;

use Neptunium\Core\DatabaseConnection;
use Neptunium\Core\ModelClasses\BaseModel;
use Neptunium\Core\ORM\Mapping\Column;
use Neptunium\Core\ORM\Mapping\FieldPropertyType;
use Neptunium\Core\ORM\Mapping\Table;

#[Table(
    name: 'UserServers',
    comment: 'Tabela z serwerami użytkownika',
    collate: 'utf8mb4_unicode_ci'
)]
class UserServerModel extends BaseModel {
    #[Column(
        type: FieldPropertyType::Integer,
        primaryKey: true,
        autoIncrement: true,
        comment: 'ID serwera'
    )]
    public int $id;

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'Nazwa serwera'
    )]
    public string $name = "";

    #[Column(
        type: FieldPropertyType::Boolean,
        comment: 'Aktywny serwer'
    )]
    public bool $active = false;

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 120,
        comment: 'URL serwera'
    )]
    public string $url = "";

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 30,
        comment: 'Wersja serwera'
    )]
    public string $version = "";

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'Nazwa hosta'
    )]
    public string $hostName = "";

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'Nazwa domeny'
    )]
    public string $domainName = "";

    #[Column(
        type: FieldPropertyType::Timestamp,
        nullable: true,
        comment: 'Data zarejestrowania'
    )]
    public string $registerDate;

    #[Column(
        type: FieldPropertyType::Timestamp,
        nullable: true,
        comment: 'Data ostatniej aktualizacji ustawień'
    )]
    public string $lastUpdate = "";

    public function __construct() {
        parent::__construct();
    }

    public function add(DatabaseConnection $databaseConnection): bool {
        $pdo = $databaseConnection->getDatabase()->getPdo();
        $query = $pdo->prepare(
            "INSERT INTO `UserServers` (`name`, `active`, `url`, `varsion`, `hostName`, `domainName`, `registerDate`, `lastUpdate`) VALUES (:servername, :active, :url, :version, :hostName, :domainName, NOW(), NOW());"
        );

        return $query->execute([
            ':servername'               => $this->name,
            ':active'                   => $this->active,
            ':url'                      => $this->url,
            ':version'                  => $this->version,
            ':hostName'                 => $this->hostName,
            ':domainName'                 => $this->domainName,
        ]);
    }

    public function update(DatabaseConnection $databaseConnection): bool {
        return false;
    }

    public function get(DatabaseConnection $databaseConnection): bool {
        return false;
    }

    public function delete(DatabaseConnection $databaseConnection): bool{
        return false;
    }
}