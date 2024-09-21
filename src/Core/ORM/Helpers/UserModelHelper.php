<?php

namespace Neptunium\Core\ORM\Helpers;

use PDO;

class UserModelHelper {
    public function add(Pdo $pdo): bool {

        // najpierw sprawdzić czy użytkownik o danym emailu istnieje

        $query = $pdo->prepare("INSERT INTO `Users` (`username`, `password`, `email`, `active`) VALUES (:username, :password, :email, :active);");
        return $query->execute([
            ':username' => $this->username,
            ':password' => $this->password,
            ':email'    => $this->email,
            ':active'   => $this->active
        ]);
    }

    public function update(Pdo $pdo): bool {
        return false;
    }

    public function get(Pdo $pdo): bool {
        return false;
    }

    public function delete(Pdo $pdo): bool {
        return false;
    }
}