<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\DatabaseConnection;
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
        $renderParams['navigationData'] = $navigationService->prepareNavigationBar('home', !!$loginData);
        $renderParams[NotificationService::NOTIFICATIONS_KEY] = $notifications;
        $renderParams[SessionService::LOGIN_DATA] = $loginData;

        $db = DatabaseConnection::getConnection()->getDatabase();
        $pdo = $db->getPdo();

        $exec = $pdo->prepare("SELECT * FROM `userservers`;");
        $exec->execute();

        $renderParams['serverList'] = $exec->fetchAll(\PDO::FETCH_ASSOC);

        return HtmlView::renderPage('index.twig', $renderParams);
    }
}
