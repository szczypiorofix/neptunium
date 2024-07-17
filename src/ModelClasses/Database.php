<?php

namespace Neptunium\ModelClasses;

use PDO;

class Database {
    private ?PDO $db = null;
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
        $this->host         = $_ENV['DB_HOST'];
        $this->name         = $_ENV['DB_NAME'];
        $this->username     = $_ENV['DB_USER'];
        $this->password     = $_ENV['DB_PASS'];
        $this->port         = $_ENV['DB_PORT'] ?? self::DEFAULT_PORT;
        $this->charset      = $_ENV['DB_CHARSET'] ?? self::DEFAULT_CHARSET;
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

    public function getDb(): ?PDO {
        return $this->db;
    }

    public function setDb(?PDO $db): void {
        $this->db = $db;
    }
}
