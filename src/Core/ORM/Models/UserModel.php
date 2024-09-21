<?php

namespace Neptunium\Core\ORM\Models;

use Neptunium\Core\ORM\Mapping\Column;
use Neptunium\Core\ORM\Mapping\FieldPropertyType;
use Neptunium\Core\ORM\Mapping\Table;
use Neptunium\Core\ORM\Models\Generic\BaseModel;

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


}
