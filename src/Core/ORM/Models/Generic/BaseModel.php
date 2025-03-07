<?php

namespace Neptunium\Core\ORM\Models\Generic;

use Neptunium\Core\DatabaseConnection;

abstract class BaseModel
{
    protected DatabaseConnection $databaseConnection;

    protected function __construct()
    {
    }
    protected function __clone()
    {
    }

    abstract public function insert(): bool;

    abstract public function update(): bool;

    abstract public function delete(): bool;

    abstract public function select(): array;
}
