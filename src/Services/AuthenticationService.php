<?php

namespace Neptunium\Services;

use Neptunium\ModelClasses\AuthInfo;
use Neptunium\ModelClasses\BaseService;

class AuthenticationService extends BaseService {

    private AuthInfo $authInfo;

    public function __construct(array $dependencies = []) {
        parent::__construct('auth', $dependencies);
    }

    public function initialize(): void {
        $this->authInfo = new AuthInfo();
    }

    public function checkInputs(array $fields, int $type): bool {
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

    /**
     * @param array $fields
     * @return string[]
     */
    public function validate(array $fields): array {
        $results = [];
        if (!$this->checkInputs($fields, INPUT_POST)) {
            $results[] = "Sprawdzanie inputów nie powiodlo się";
            return $results;
        }

        $filteredInputs = filter_input_array(INPUT_POST, $fields);
        if (!$filteredInputs) {{
            $results[] = "Filtrowanie inputów nie powiodlo się";
            return $results;
        }}

        if (!$this->checkMatchPassword($filteredInputs['userpass'])) {
            $results[] = "Błędny format hasła";
            return $results;
        }

        return $results;
    }

    public function setAuthInfo(AuthInfo $authInfo): void {
        $this->authInfo = $authInfo;
    }

    public function getAuthInfo(): AuthInfo {
        return $this->authInfo;
    }
}
