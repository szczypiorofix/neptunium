<?php

namespace Neptunium\ORM\Models;

use Neptunium\Core\DatabaseConnection;
use Neptunium\ModelClasses\BaseModel;
use Neptunium\ORM\Mapping\Column;
use Neptunium\ORM\Mapping\FieldPropertyType;
use Neptunium\ORM\Mapping\Table;

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
    public string $serverName = "";

    #[Column(
        type: FieldPropertyType::Boolean,
        comment: 'Aktywny serwer'
    )]
    public bool $active = false;

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
    public string $lastSettingsUpdateDate = "";

    public function __construct() {
        parent::__construct();
    }

    public function add(DatabaseConnection $databaseConnection): bool {
        $pdo = $databaseConnection->getDatabase()->getPdo();
        $query = $pdo->prepare("INSERT INTO `UserServers` (`serverName`, `active`, `registerDate`, `lastSettingsUpdateDate`) VALUES (:servername, :active, NOW(), NOW());");

        return $query->execute([
            ':servername'               => $this->serverName,
            ':active'                   => $this->active
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