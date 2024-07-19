<?php

namespace Neptunium\Models;

use Neptunium\ModelClasses\BaseModel;

class UserModel extends BaseModel {

    public string $username;
    public string $password;
    public string $email;
    public string $firstName;
    public string $lastName;

    public function __construct() {
        parent::__construct();
    }
}
