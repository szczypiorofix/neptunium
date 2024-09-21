<?php

namespace Neptunium\Core\ModelClasses;

use Neptunium\Config;
use PDO;

class Database {
    private ?PDO $pdo = null;
    private string $errorMessage = "";
    private bool $error = false;
    private string $host;
    private string $name;
    private int $port;
    private string $username;
    private string $password;
    private string $charset;

    private const DEFAULT_PORT = 3306;
    private const DEFAULT_CHARSET = 'UTF8';

    public function __construct() {
        $this->host         = getenv(Config::ENV_NEP_DB_HOST);
        $this->name         = getenv(Config::ENV_NEP_DB_NAME);
        $this->username     = getenv(Config::ENV_NEP_DB_USER);
        $this->password     = getenv(Config::ENV_NEP_DB_PASS);
        $this->port         = getenv(Config::ENV_NEP_DB_PORT) ?? self::DEFAULT_PORT;
        $this->charset      = getenv(Config::ENV_NEP_DB_CHARSET) ?? self::DEFAULT_CHARSET;
    }

    public function getCharset(): string {
        return $this->charset;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPort(): int {
        return $this->port;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getHost(): string {
        return $this->host;
    }

    public function isError(): bool {
        return $this->error;
    }

    public function setError(bool $error): void {
        $this->error = $error;
    }

    public function getErrorMessage(): string {
        return $this->errorMessage;
    }

    public function setErrorMessage(string $errorMessage): void {
        $this->errorMessage = $errorMessage;
    }

    public function getPdo(): ?PDO {
        return $this->pdo;
    }

    public function setPdo(?PDO $pdo): void {
        $this->pdo = $pdo;
    }
}
