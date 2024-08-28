<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
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
            'queryData'         => $params,
        ];

        $serviceManager = ServiceManager::getInstance();

        $sessionService = $serviceManager->getSessionService();
        $sessionService->sessionStart();

        $navigationService = $serviceManager->getNavigationService();
        $renderParams['navigationData'] = $navigationService->prepareNavigationBar('login');

//        $notificationService = $serviceManager->getNotificationService();

        return HtmlView::renderPage('index.twig', $renderParams);
    }
}