<?php

namespace Neptunium\Services;

use Neptunium\ModelClasses\BaseService;

class SessionService extends BaseService {

    const LOGIN_DATA = "loginData";

    public function __construct(array $dependencies = []) {
        parent::__construct('session', $dependencies);
    }

    public function initialize(): void {

    }

    public function sessionStart(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function sessionDestroy(): void {
        session_destroy();
    }

    public function getLoginData(): string {
        return $this->getSessionData(self::LOGIN_DATA);
    }

    public function setLoginData(): void {
        $this->setSessionData(self::LOGIN_DATA, '1');
    }

    public function unsetLoginData(): void {
        $this->setSessionData(self::LOGIN_DATA, '0');
    }

    public function getSessionData(string $key): string {
        if (
            session_status() === PHP_SESSION_ACTIVE
            && isset($_SESSION[$key])
        ) {
            return $_SESSION[$key];
        }
        return '';
    }

    public function setSessionData(string $key, string $value): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION[$key] = $value;
        }
    }
}
