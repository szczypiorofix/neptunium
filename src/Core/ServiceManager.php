<?php

namespace Neptunium\Core;

use Neptunium\Services\AuthenticationService;
use Neptunium\Services\NavigationService;
use Neptunium\Services\NotificationService;
use Neptunium\Services\SessionService;

class ServiceManager {
    private static ServiceManager $instance;

    private AuthenticationService $authenticationService;
    private NotificationService $notificationService;
    private SessionService $sessionService;
    private NavigationService $navigationService;

    private function __construct() {}

    public static function getInstance(): ServiceManager {
        if (!isset(self::$instance)) {
            self::$instance = new ServiceManager();
        }
        return self::$instance;
    }

    public function init(
        AuthenticationService $authenticationService,
        SessionService $sessionService,
        NotificationService $notificationService,
        NavigationService $navigationService,
    ): void {
        $this->authenticationService = $authenticationService;
        $this->sessionService = $sessionService;
        $this->notificationService = $notificationService;
        $this->navigationService = $navigationService;

        $this->initializeServices();
    }

    public function getAuthenticationService(): AuthenticationService {
        return $this->authenticationService;
    }

    public function getSessionService(): SessionService {
        return $this->sessionService;
    }

    public function getNotificationService(): NotificationService {
        return $this->notificationService;
    }

    public function getNavigationService(): NavigationService {
        return $this->navigationService;
    }

    private function initializeServices(): void {
        $this->authenticationService->initialize();
        $this->sessionService->initialize();
        $this->notificationService->initialize();
        $this->navigationService->initialize();
    }
}