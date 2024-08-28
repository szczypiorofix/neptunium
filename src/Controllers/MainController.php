<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ServiceManager;
use Neptunium\ModelClasses\Controller;
use Neptunium\ModelClasses\Http;
use Neptunium\Services\SessionService;

class MainController extends Controller {
    #[Route('/', Http::GET)]
    public function index(array $params = []): string {
        $renderParams = [
            'templateFileName'  => 'main.twig',
            'templateName'      => 'main',
            'queryData'         => $params,
            'sessionData'       => $_SESSION ?? [],
        ];

        $serviceManager = ServiceManager::getInstance();

        $sessionService = $serviceManager->getSessionService();
        $sessionService->sessionStart();
        $loginData = $sessionService->getLoginData();

        $renderParams[SessionService::LOGIN_DATA] = $loginData;

        $navigationService = $serviceManager->getNavigationService();
        $renderParams['navigationData'] = $navigationService->prepareNavigationBar('main', !!$loginData);

        return HtmlView::renderPage('index.twig', $renderParams);
    }
}
