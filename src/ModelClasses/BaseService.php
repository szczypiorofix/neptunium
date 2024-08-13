<?php

namespace Neptunium\ModelClasses;

use Neptunium\Interfaces\Injectable;

abstract class BaseService {
    /**
     * @param Injectable[] $dependencies
     */
    public function __construct(protected string $name, array $dependencies = []) {}

    abstract function initialize(): void;

    public function getName(): string {
        return $this->name;
    }
}
