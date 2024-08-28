<?php

namespace Neptunium\Services;

use Neptunium\Core\DatabaseConnection;
use Neptunium\ModelClasses\AuthInfo;
use Neptunium\ModelClasses\BaseService;

class AuthenticationService extends BaseService {
    private DatabaseConnection $databaseConnection;
    private AuthInfo $authInfo;

    public function __construct(array $dependencies = []) {
        parent::__construct('auth', $dependencies);
    }

    public function initialize(): void {
        $this->authInfo = new AuthInfo();
    }

    public function setDatabaseConnection(DatabaseConnection $dbConnection): void {
        $this->databaseConnection = $dbConnection;
    }

    /**
     * @param array $fields
     * @return array
     */
    public function validate(array $fields): array {
        $results = [];
        if (!$this->checkInputs($fields, INPUT_POST)) {
            $results['error'] = "Sprawdzanie inputów nie powiodlo się";
            return $results;
        }

        $filteredInputs = filter_input_array(INPUT_POST, $fields);
        if (!$filteredInputs) {{
            $results['error'] = "Filtrowanie inputów nie powiodlo się";
            return $results;
        }}

        if (!$this->checkMatchPassword($filteredInputs['userpass'])) {
            $results['error'] = "Błędny format hasła";
            return $results;
        }

        $results['userdata'] = $this->checkUser($filteredInputs['useremail'], $filteredInputs['userpass']);
        return  $results;
    }

    public function setAuthInfo(AuthInfo $authInfo): void {
        $this->authInfo = $authInfo;
    }

    public function getAuthInfo(): AuthInfo {
        return $this->authInfo;
    }

    public function setUserLastLoginTime($userEmail): bool {
        $pdo = $this->databaseConnection->getDatabase()->getPdo();
        $exec = $pdo->prepare("UPDATE `users` SET `lastLogin`=NOW() WHERE `email` = :useremail");
        $exec->execute([
            ':useremail' => $userEmail,
        ]);
        return false;
    }

    private function checkInputs(array $fields, int $type): bool {
        foreach($fields as $field => $filter) {
            if (filter_input($type, $field, $filter) == null) {
                return false;
            }
        }
        return true;
    }

    private function checkMatchPassword($password): bool {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
        if (preg_match($pattern, $password)) {
            return true;
        }
        return false;
    }

    private function checkUser(string $userEmail, string $userPass): array {
        $pdo = $this->databaseConnection->getDatabase()->getPdo();
        $exec = $pdo->prepare("SELECT * FROM `users` WHERE `email` = :useremail AND `password` = MD5(:userpass)");
        $exec->execute([
            ':useremail' => $userEmail,
            ':userpass' => $userPass
        ]);
        return $exec->fetchAll(\PDO::FETCH_ASSOC);
    }
}
