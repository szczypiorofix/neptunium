<?php

namespace Neptunium\ModelClasses;

use Neptunium\Core\DatabaseConnection;

interface Generator {
    public function generate(string $class, DatabaseConnection $databaseConnection): bool;
}
