<?php

namespace Neptunium\Controllers;

use Neptunium\Core\Attributes\Route;
use Neptunium\Core\DatabaseConnection;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ModelClasses\Controller;
use Neptunium\Core\ModelClasses\Http;
use Neptunium\Core\ServiceManager;
use Neptunium\Core\Services\NotificationService;
use Neptunium\Core\Services\SessionService;


class AdminPageController extends Controller {
    #[Route('/admin', Http::GET)]
    public function index(array $params = []): string {
        $renderParams = [
            'templateFileName'  => 'admin.twig',
            'templateName'      => 'admin',
            'queryData'         => $params,
        ];

        $serviceManager = ServiceManager::getInstance();

        $sessionService = $serviceManager->getSessionService();
        $sessionService->sessionStart();
        $loginData = $sessionService->getLoginData();

//        $loginValue = intval($loginData);
//        if (!$loginValue) {
//            $this->redirect(NEP_BASE_URL . "/");
//        }

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
