<?php

namespace Neptunium\Core\ModelClasses;

use Neptunium\Core\DatabaseConnection;

abstract class BaseModel {
    public function __construct() {}

    public abstract function add(DatabaseConnection $databaseConnection): bool;
    public abstract function update(DatabaseConnection $databaseConnection): bool;
    public abstract function get(DatabaseConnection $databaseConnection): bool;
    public abstract function delete(DatabaseConnection $databaseConnection): bool;
}
