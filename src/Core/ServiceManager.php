<?php

namespace Neptunium\Core;

use Neptunium\Core\ModelClasses\BaseService;

class ServiceManager {
    
    /**
     * @var BaseService[] array
     */
    private array $services;

    public function __construct() {}
    public function __clone() {}

    /**
     * @param BaseService[] $services
     * @return void
     */
    public function init(array $services): void {
        $this->services = $services;
        $this->initializeServices();
    }

    public function addService(string $name, BaseService $service): void {
        $this->services[$name] = $service;
    }

    public function getService(string $className): BaseService | null {
        if (isset($this->services[$className])) {
            return $this->services[$className];
        }
        return null;
    }

    private function initializeServices(): void {
        foreach ($this->services as $service) {
            $service->initialize();
        }
    }
}