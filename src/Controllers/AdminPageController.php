<?php

namespace Neptunium\Controllers;

use Neptunium\Core\Attributes\Route;
use Neptunium\Core\DatabaseConnection;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ModelClasses\Controller;
use Neptunium\Core\ModelClasses\FrameworkException;
use Neptunium\Core\ModelClasses\Http;
use Neptunium\Core\ServiceManager;
use Neptunium\Core\Services\NavigationService;
use Neptunium\Core\Services\NotificationService;
use Neptunium\Core\Services\SessionService;
use Neptunium\Core\ModelClasses\RenderParamsEnum;

class AdminPageController extends Controller {
    /**
     * @throws FrameworkException
     */
    #[Route('/admin', Http::GET)]
    public function index(array $params = []): string {
        $renderParams = [
            'templateFileName'  => 'admin.twig',
            'templateName'      => 'admin',
            'queryData'         => $params,
        ];

        $serviceManager = ServiceManager::getInstance();

        $sessionService = $serviceManager->getService(SessionService::$name);
        if (!$sessionService instanceof SessionService) {
            throw new FrameworkException('Service error!', 'Session service not found');
        }

        $sessionService->sessionStart();
        $loginData = $sessionService->getLoginData();

        $notificationService = $serviceManager->getService(NotificationService::$name);
        if (!$notificationService instanceof NotificationService) {
            throw new FrameworkException('Service error!', 'Notification service not found');
        }
        $notificationService->restoreNotifications();
        $notifications = $notificationService->getNotifications();
        $notificationService->clearNotifications();

        $navigationService = $serviceManager->getService(NavigationService::$name);
        if (!$navigationService instanceof NavigationService) {
            throw new FrameworkException('Service error!', 'Navigation service not found');
        }
        $renderParams['navigationData'] = $navigationService->prepareNavigationBar('home', !!$loginData);
        $renderParams[RenderParamsEnum::NOTIFICATIONS->value] = $notifications;
        $renderParams[RenderParamsEnum::LOGIN_DATA->value] = $loginData;

        $db = DatabaseConnection::getConnection()->getDatabase();
        $pdo = $db->getPdo();

        $exec = $pdo->prepare("SELECT * FROM `userservers`;");
        $exec->execute();

        $renderParams['serverList'] = $exec->fetchAll(\PDO::FETCH_ASSOC);

        return HtmlView::renderPage('index.twig', $renderParams);
    }
}
