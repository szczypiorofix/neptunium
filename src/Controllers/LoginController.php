<?php

namespace Neptunium\Controllers;

use Neptunium\Core\Attributes\Route;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ModelClasses\Controller;
use Neptunium\Core\ModelClasses\Http;
use Neptunium\Core\ServiceManager;
use Neptunium\Core\Services\NotificationService;

class LoginController extends Controller {
    #[Route('/login', Http::GET)]
    public function index(array $params = []): string {
        $renderParams = [
            'templateFileName'  => 'login.twig',
            'templateName'      => 'login',
            'queryData'         => $params,
        ];

        $serviceManager = ServiceManager::getInstance();

        $sessionService = $serviceManager->getSessionService();
        $sessionService->sessionStart();
        $loginData = $sessionService->getLoginData();

        $notificationService = $serviceManager->getNotificationService();
        $notificationService->restoreNotifications();
        $notifications = $notificationService->getNotifications();
        $notificationService->clearNotifications();

        $navigationService = $serviceManager->getNavigationService();
        $renderParams['navigationData'] = $navigationService->prepareNavigationBar('login', !!$loginData);
        $renderParams[NotificationService::NOTIFICATIONS_KEY] = $notifications;

        return HtmlView::renderPage('index.twig', $renderParams);
    }
}