<?php

namespace Neptunium\Core\Services;

use Neptunium\Core\ModelClasses\BaseService;
use Neptunium\Core\ModelClasses\RenderParamsEnum;

class SessionService extends BaseService {
    public static string $name = 'SessionService';

    public function __construct(array $dependencies = []) {
        parent::__construct(self::$name, $dependencies);
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
        return $this->getSessionData(RenderParamsEnum::LOGIN_DATA);
    }

    public function setLoginData(): void {
        $this->setSessionData(RenderParamsEnum::LOGIN_DATA, '1');
    }

    public function unsetLoginData(): void {
        $this->setSessionData(RenderParamsEnum::LOGIN_DATA, '0');
    }

    public function getSessionData(RenderParamsEnum $key): string {
        if (
            session_status() === PHP_SESSION_ACTIVE
            && isset($_SESSION[$key->value])
        ) {
            return $_SESSION[$key->value];
        }
        return '';
    }

    public function setSessionData(RenderParamsEnum $param, string $value): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION[$param->value] = $value;
        }
    }
}
