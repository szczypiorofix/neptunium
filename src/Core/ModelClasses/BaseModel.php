<?php

namespace Neptunium\Core\ModelClasses;

use Neptunium\Core\DatabaseConnection;

abstract class BaseModel
{
    public function __construct()
    {
    }

    abstract public function add(DatabaseConnection $databaseConnection): bool;
    abstract public function update(DatabaseConnection $databaseConnection): bool;
    abstract public function get(DatabaseConnection $databaseConnection): bool;
    abstract public function delete(DatabaseConnection $databaseConnection): bool;
}
