<?php

namespace Neptunium\Core\ORM\Helpers;

use Neptunium\Core\DatabaseConnection;
use Neptunium\Core\ModelClasses\FrameworkException;
use Neptunium\Core\ORM\Models\UserModel;
use PDO;

class UserModelHelper {
    /**
     * @throws FrameworkException
     */
    public static function add(UserModel $user): bool {
        $pdo = self::getPDO();
        if ($pdo) {

            // najpierw sprawdzić czy użytkownik o danym emailu istnieje

            $query = $pdo->prepare("INSERT INTO `Users` (`username`, `password`, `email`, `active`) VALUES (:username, :password, :email, :active);");
            return $query->execute([
                ':username' => $user->username,
                ':password' => $user->password,
                ':email'    => $user->email,
                ':active'   => $user->active
            ]);
        }
        throw new FrameworkException("PDO Object is null!", "PDO Object is null!");
    }

    public static function update(): bool {
        $pdo = self::getPDO();
        return false;
    }

    public static function get(): bool {
        $pdo = self::getPDO();
        return false;
    }

    public static function delete(): bool {
        $pdo = self::getPDO();
        return false;
    }

    private static function getPDO(): ?PDO {
        $databaseConnection = DatabaseConnection::getConnection();
        return $databaseConnection->getDatabase()->getPDO();
    }
}
