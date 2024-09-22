<?php

namespace Neptunium\Core;

use Neptunium\Core\ModelClasses\BaseService;
use Neptunium\Core\Services\AuthenticationService;
use Neptunium\Core\Services\NavigationService;
use Neptunium\Core\Services\NotificationService;
use Neptunium\Core\Services\SessionService;

class ServiceManager {
    private static ServiceManager $instance;

    /**
     * @var BaseService[] array
     */
    private array $services;

    private function __construct() {}

    public static function getInstance(): ServiceManager {
        if (!isset(self::$instance)) {
            self::$instance = new ServiceManager();
        }
        return self::$instance;
    }

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