<?php

namespace Neptunium\ORM\Models;

use Neptunium\Core\DatabaseConnection;
use Neptunium\ModelClasses\BaseModel;
use Neptunium\ORM\Mapping\Column;
use Neptunium\ORM\Mapping\FieldPropertyType;
use Neptunium\ORM\Mapping\Table;
use PDO;

#[Table(
    name: 'Users',
    comment: 'Tabela użytkowników',
    collate: 'utf8mb4_unicode_ci'
)]
class UserModel extends BaseModel {
    #[Column(
        type: FieldPropertyType::Integer,
        primaryKey: true,
        autoIncrement: true,
        comment: 'ID użytkownika'
    )]
    public int $id;

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'Nazwa użytkownika'
    )]
    public string $username = "";

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 32,
        comment: 'Hasło użytkownika'
    )]
    public string $password = "";

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'E-mail użytkownika'
    )]
    public string $email = "";

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'Imię użytkownika'
    )]
    public string $firstName = "";

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'Nazwisko użytkownika'
    )]
    public string $lastName = "";

    #[Column(
        type: FieldPropertyType::Boolean,
        comment: 'Aktywny uzytkownik'
    )]
    public bool $active = false;

    #[Column(
        type: FieldPropertyType::Timestamp,
        nullable: true,
        comment: 'Data rejestracji'
    )]
    public string $registered = "";

    #[Column(
        type: FieldPropertyType::Timestamp,
        nullable: true,
        comment: 'Data ostatniego logowania'
    )]
    public string $lastLogin = "";

    public function __construct() {
        parent::__construct();
    }

    public function add(DatabaseConnection $databaseConnection): bool {
        $pdo = $databaseConnection->getDatabase()->getPdo();
        $query = $pdo->prepare("INSERT INTO `Users` (`username`, `password`, `email`, `active`) VALUES (:username, :password, :email, :active);");
        return $query->execute([
            ':username' => $this->username,
            ':password' => $this->password,
            ':email'    => $this->email,
            ':active'   => $this->active
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
