<?php

namespace Neptunium\Controllers;

use Neptunium\Core\Attributes\Route;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ModelClasses\Controller;
use Neptunium\Core\ModelClasses\FrameworkException;
use Neptunium\Core\ModelClasses\Http;
use Neptunium\Core\ServiceManager;
use Neptunium\Core\Services\NavigationService;
use Neptunium\Core\Services\SessionService;

class MainController extends Controller {
    /**
     * @throws FrameworkException
     */
    #[Route('/', Http::GET)]
    public function index(array $params = []): string {
        $renderParams = [
            'templateFileName'  => 'main.twig',
            'templateName'      => 'main',
            'queryData'         => $params,
            'sessionData'       => $_SESSION ?? [],
        ];

        $serviceManager = ServiceManager::getInstance();

        $sessionService = $serviceManager->getService(SessionService::$name);
        if (!$sessionService instanceof SessionService) {
            throw new FrameworkException('Service error!', 'Session service not found');
        }
        $sessionService->sessionStart();
        $loginData = $sessionService->getLoginData();

        $renderParams[SessionService::LOGIN_DATA] = $loginData;

        $navigationService = $serviceManager->getService(NavigationService::$name);
        if (!$navigationService instanceof NavigationService) {
            throw new FrameworkException('Service error!', 'Navigation service not found');
        }
        $renderParams['navigationData'] = $navigationService->prepareNavigationBar('main', !!$loginData);

        return HtmlView::renderPage('index.twig', $renderParams);
    }
}
