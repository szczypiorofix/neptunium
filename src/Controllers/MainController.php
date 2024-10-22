<?php

namespace Neptunium\Controllers;

use Neptunium\Core\Attributes\Route;
use Neptunium\Core\DebugContainer;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ModelClasses\Controller;
use Neptunium\Core\ModelClasses\FrameworkException;
use Neptunium\Core\ModelClasses\Http;
use Neptunium\Core\RenderParams;
use Neptunium\Core\ServiceManager;
use Neptunium\Core\Services\NavigationService;
use Neptunium\Core\Services\NotificationService;
use Neptunium\Core\Services\SessionService;
use Neptunium\Core\ModelClasses\RenderParamsEnum;

class MainController extends Controller {
    /**
     * @throws FrameworkException
     */
    #[Route('/', Http::GET)]
    public function index(array $params = []): string {
        RenderParams::set(
            [
                'templateFileName'  => 'main.twig',
                'templateName'      => 'main',
                'queryData'         => $params,
                'sessionData'       => $_SESSION ?? [],
            ]
        );
        
        $serviceManager = ServiceManager::getInstance();

        $sessionService = $serviceManager->getService(SessionService::$name);
        if (!$sessionService instanceof SessionService) {
            throw new FrameworkException('Service error!', 'Session service not found');
        }
        $sessionService->sessionStart();
        $loginData = $sessionService->getLoginData();
        RenderParams::set([RenderParamsEnum::LOGIN_DATA->value => $loginData]);

        $navigationService = $serviceManager->getService(NavigationService::$name);
        if (!$navigationService instanceof NavigationService) {
            throw new FrameworkException('Service error!', 'Navigation service not found');
        }
        RenderParams::set([RenderParamsEnum::NAVIGATION_DATA->value => $navigationService->prepareNavigationBar('main', !!$loginData)]);

        $notificationService = $serviceManager->getService(NotificationService::$name);
        if (!$notificationService instanceof NotificationService) {
            throw new FrameworkException('Service error!', 'Notification service not found');
        }
        $notificationService->restoreNotifications();
        $notifications = $notificationService->getNotifications();
        $notificationService->clearNotifications();
        RenderParams::set([RenderParamsEnum::NOTIFICATIONS->value => $notifications]);

        DebugContainer::$warning = [
          'main'=> 'Notifications count: '.count($notifications),
        ];

        return HtmlView::renderPage('index.twig', RenderParams::getAll());
    }
}
