<?php

namespace Neptunium\Core;

use Neptunium\ModelClasses\BaseService;
use Neptunium\Services\AuthenticationService;

class ServiceManager {

    /**
     * @var BaseService[]
     */
    private array $services = [];

    public function __construct() {}

    public function init(): void {
        $authenticationService = new AuthenticationService();
        $authenticationService->initialize();
        $this->addService(($authenticationService));
    }

    public function getService(string $name): BaseService {
        return $this->services[$name];
    }

    private function addService(BaseService $service): void {
        $serviceName = $service->getName();
        $this->services[$serviceName] = $service;
    }

}