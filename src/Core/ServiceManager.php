<?php

namespace Neptunium\Core;

use Neptunium\ModelClasses\BaseService;
use Neptunium\Services\AuthenticationService;
use Neptunium\Services\NotificationService;
use Neptunium\Services\SessionService;

class ServiceManager {
    private static ServiceManager $instance;

    private AuthenticationService $authenticationService;
    private NotificationService $notificationService;
    private SessionService $sessionService;

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
        NotificationService $notificationService
    ): void {
        $this->authenticationService = $authenticationService;
        $this->sessionService = $sessionService;
        $this->notificationService = $notificationService;
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
}