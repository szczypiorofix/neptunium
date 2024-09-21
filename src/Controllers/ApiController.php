<?php

namespace Neptunium\Controllers;

use JetBrains\PhpStorm\NoReturn;
use Neptunium\Core\Attributes\Route;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ModelClasses\Controller;
use Neptunium\Core\ModelClasses\Http;
use Neptunium\Core\ModelClasses\NotificationType;
use Neptunium\Core\ServiceManager;

class ApiController extends Controller {
    #[Route('/api', Http::GET)]
    public function index(array $params = []): string {
        return HtmlView::renderPage('index.twig',
            [
                'templateFileName' => 'api.twig',
                'templateName' => 'api',
                'queryData' => $params,
            ]
        );
    }

    #[NoReturn]
    #[Route('/api/login', Http::POST)]
    public function login(array $params = []): string {
        $serviceManager = ServiceManager::getInstance();

        $sessionService = $serviceManager->getSessionService();
        $sessionService->sessionStart();

        $inputsToValidate = array(
            'useremail' => FILTER_VALIDATE_EMAIL,
            'userpass'  => FILTER_SANITIZE_SPECIAL_CHARS,
        );

        $authService = $serviceManager->getAuthenticationService();
        $results = $authService->validate($inputsToValidate);

        $notificationService = $serviceManager->getNotificationService();

        if (isset($results['error'])) {
            $notificationService->addNotification('login', $results['error'], NotificationType::ERROR);
            $notificationService->saveNotifications();
            $sessionService->unsetLoginData();

            $this->redirect(NEP_BASE_URL . "/login/");
        }

        if (isset($results['userdata']) && count($results['userdata']) === 1) {
            if (isset($results['userdata'][0]['email'])) {
                $authService->setUserLastLoginTime($results['userdata'][0]['email']);

                $notificationService->addNotification('login', "Użytkownik pomyślnie zalogoway", NotificationType::INFO);
                $notificationService->saveNotifications();
                $sessionService->setLoginData();
            }

            $this->redirect(NEP_BASE_URL . "/admin");
        }

        $notificationService->addNotification('login', 'Zły login i/lub hasło. Spróbuj ponownie.', NotificationType::ERROR);
        $notificationService->saveNotifications();
        $sessionService->unsetLoginData();

        $this->redirect(NEP_BASE_URL . "/login/");
    }

    #[NoReturn]
    #[Route('/api/logout', Http::GET)]
    public function logout(array $params = []): string {
        $serviceManager = ServiceManager::getInstance();

        $sessionService = $serviceManager->getSessionService();
        $sessionService->sessionStart();

        $sessionService->unsetLoginData();

        $notificationService = $serviceManager->getNotificationService();
        $notificationService->addNotification('logout', "Użytkownik wylogowany", NotificationType::INFO);

        $notificationService->saveNotifications();

        $this->redirect(NEP_BASE_URL . "/");
    }
}