<?php

namespace Neptunium\Core;

use PDO;

class Database {
    public function __construct(
        public PDO $db,
        public string $errorMessage,
        public bool $error
    ) {}
}
