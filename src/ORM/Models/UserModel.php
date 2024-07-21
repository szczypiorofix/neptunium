<?php

namespace Neptunium\ORM\Models;

use Neptunium\ModelClasses\BaseModel;
use Neptunium\ORM\Mapping\Column;
use Neptunium\ORM\Mapping\FieldPropertyType;
use Neptunium\ORM\Mapping\Table;

#[Table('User')]
class UserModel extends BaseModel {
    #[Column(
        type: FieldPropertyType::Integer,
        autoIncrement: true,
        comment: 'ID użytkownika'
    )]
    public int $id;

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'Nazwa użytkownika'
    )]
    public string $username;

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 20,
        comment: 'Hasło użytkownika'
    )]
    public string $password;

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'E-mail użytkownika'
    )]
    public string $email;

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'Imię użytkownika'
    )]
    public string $firstName;

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'Nazwisko użytkownika'
    )]
    public string $lastName;

    #[Column(
        type: FieldPropertyType::Boolean,
        comment: 'Aktywny uzytkownik'
    )]
    public bool $active;

    #[Column(
        type: FieldPropertyType::Timestamp,
        nullable: true,
        comment: 'Data rejestracji'
    )]
    public bool $registered;

    #[Column(
        type: FieldPropertyType::Timestamp,
        nullable: true,
        comment: 'Data ostatniego logowania'
    )]
    public bool $lastLogin;

    public function __construct() {
        parent::__construct();
    }
}
