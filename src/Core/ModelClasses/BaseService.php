<?php

namespace Neptunium\Core\ModelClasses;

use Neptunium\Core\Interfaces\Injectable;

abstract class BaseService {
    /**
     * @param Injectable[] $dependencies
     */
    public function __construct(protected string $serviceName, array $dependencies = []) {}

    abstract public function initialize(): void;

    public function getServiceName(): string {
        return $this->serviceName;
    }
}
