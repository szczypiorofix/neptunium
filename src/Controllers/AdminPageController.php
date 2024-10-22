<?php

namespace Neptunium\Controllers;

use Neptunium\Core\Attributes\Route;
use Neptunium\Core\DatabaseConnection;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ModelClasses\Controller;
use Neptunium\Core\ModelClasses\FrameworkException;
use Neptunium\Core\ModelClasses\Http;
use Neptunium\Core\ModelClasses\RenderParamsEnum;
use Neptunium\Core\RenderParams;
use Neptunium\Core\ServiceManager;
use Neptunium\Core\Services\NavigationService;
use Neptunium\Core\Services\NotificationService;
use Neptunium\Core\Services\SessionService;
use PDO;

class AdminPageController extends Controller {
    /**
     * @throws FrameworkException
     */
    #[Route('/admin', Http::GET)]
    public function index(
        ServiceManager $serviceManager,
        array $params = []
    ): string {
        RenderParams::set([
            RenderParamsEnum::TEMPLATE_FILE_NAME->value => 'admin.twig',
            RenderParamsEnum::TEMPLATE_NAME->value      => 'admin',
            RenderParamsEnum::QUERY_DATA->value         => $params,
        ]);

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
        
        RenderParams::set([
            RenderParamsEnum::NAVIGATION_DATA->value => $navigationService->prepareNavigationBar('home', !!$loginData),
            RenderParamsEnum::NOTIFICATIONS->value => $notifications,
            RenderParamsEnum::LOGIN_DATA->value => $loginData,
        ]);

        $db = DatabaseConnection::getConnection()->getDatabase();
        $pdo = $db->getPdo();

        $exec = $pdo->prepare("SELECT * FROM `userservers`;");
        $exec->execute();

        RenderParams::set([
            RenderParamsEnum::SERVER_LIST->value => $exec->fetchAll(PDO::FETCH_ASSOC)
        ]);
        return HtmlView::renderPage('index.twig', RenderParams::getAll());
    }
}
