<?php

namespace Neptunium\ModelClasses;

abstract class BaseModel {
    public function __construct() {
        print_r("Generate object");
    }

    public abstract function add(): bool;
    public abstract function update(): bool;
    public abstract function get(): bool;
    public abstract function delete(): bool;
}
