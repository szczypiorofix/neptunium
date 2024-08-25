<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\DebugContainer;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ServiceManager;
use Neptunium\ModelClasses\Controller;
use Neptunium\ModelClasses\Http;
use Neptunium\Services\NotificationService;
use Neptunium\Services\SessionService;


class HomeController extends Controller {
    #[Route('/home', Http::GET)]
    public function index(array $params = []): string {
        $renderParams = [
            'templateFileName'  => 'home.twig',
            'templateName'      => 'home',
            'debugInfoData'     => DebugContainer::$info,
            'debugErrorData'    => DebugContainer::$error,
            'queryData'         => $params,
        ];

        $serviceManager = ServiceManager::getInstance();
        $sessionService = $serviceManager->getSessionService();
        $sessionService->sessionStart();

        $notificationService = $serviceManager->getNotificationService();

        $notificationService->restoreNotifications();

        $loginData = $sessionService->getLoginData();

        // Session
        $renderParams[SessionService::LOGIN_DATA] = $loginData;

        $notifications = $notificationService->getNotifications();

        echo '<pre>';
        print_r($notificationService->getNotifications());
        echo '</pre>';

        $renderParams[NotificationService::NOTIFICATIONS_KEY] = $notifications;
        $notificationService->clearNotifications();

        return HtmlView::renderPage('index.twig', $renderParams);
    }
}
