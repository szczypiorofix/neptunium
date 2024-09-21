<?php

namespace Neptunium\Core\ORM\Models\Generic;

class BaseModel {
    protected function __construct() {}
    protected function __clone() {}

    public function insert(): bool {
        return false;
    }

    public function update(): bool {
        return false;
    }

    public function delete(): bool {
        return false;
    }

    public function select(): array {
        return array();
    }
}
