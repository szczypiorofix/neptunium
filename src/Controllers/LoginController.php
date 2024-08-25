<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\DebugContainer;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ServiceManager;
use Neptunium\ModelClasses\Controller;
use Neptunium\ModelClasses\Http;

class LoginController extends Controller {
    #[Route('/login', Http::GET)]
    public function index(array $params = []): string {
        $renderParams = [
            'templateFileName'  => 'login.twig',
            'templateName'      => 'login',
            'debugInfoData'     => DebugContainer::$info,
            'debugErrorData'    => DebugContainer::$error,
            'queryData'         => $params,
        ];

        $serviceManager = ServiceManager::getInstance();
        $sessionService = $serviceManager->getSessionService();
        $sessionService->sessionStart();

        $notificationService = $serviceManager->getNotificationService();

        echo '<pre>';
        print_r($notificationService->getNotifications());
        echo '</pre>';

        return HtmlView::renderPage('index.twig', $renderParams);
    }
}