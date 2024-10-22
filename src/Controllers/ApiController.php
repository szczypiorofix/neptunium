<?php

namespace Neptunium\Controllers;

use Neptunium\Core\Attributes\Route;
use Neptunium\Core\HtmlView;
use Neptunium\Core\ModelClasses\Controller;
use Neptunium\Core\ModelClasses\FrameworkException;
use Neptunium\Core\ModelClasses\Http;
use Neptunium\Core\ModelClasses\NotificationType;
use Neptunium\Core\ModelClasses\RenderParamsEnum;
use Neptunium\Core\RenderParams;
use Neptunium\Core\ServiceManager;
use Neptunium\Core\Services\AuthenticationService;
use Neptunium\Core\Services\NotificationService;
use Neptunium\Core\Services\SessionService;

class ApiController extends Controller {
    #[Route('/api', Http::GET)]
    public function index(
        ServiceManager $serviceManager,
        array $params = []
    ): string {
        RenderParams::set([
            RenderParamsEnum::TEMPLATE_FILE_NAME->value => 'api.twig',
            RenderParamsEnum::TEMPLATE_NAME->value      => 'api',
            RenderParamsEnum::QUERY_DATA->value         => $params,
        ]);
        return HtmlView::renderPage('index.twig', RenderParams::getAll());
    }

    /**
     * @throws FrameworkException
     */
    #[Route('/api/login', Http::POST)]
    public function login(
        ServiceManager $serviceManager,
        array $params = []
    ): void {        
        $sessionService = $serviceManager->getService(SessionService::$name);
        if (!$sessionService instanceof SessionService) {
            throw new FrameworkException('Service error!', 'Session service not found');
        }
        $sessionService->sessionStart();

        $inputsToValidate = array(
            'useremail' => FILTER_VALIDATE_EMAIL,
            'userpass'  => FILTER_SANITIZE_SPECIAL_CHARS,
        );

        $authService = $serviceManager->getService(AuthenticationService::$name);
        if (!$authService instanceof AuthenticationService) {
            throw new FrameworkException('Service error!', 'Authentication service not found');
        }
        $results = $authService->validate($inputsToValidate);

        $notificationService = $serviceManager->getService(NotificationService::$name);
        if (!$notificationService instanceof NotificationService) {
            throw new FrameworkException('Service error!', 'Notification service not found');
        }

        if (isset($results['error'])) {
            $this->setNotificationAndLogout(
                $notificationService, 
                $sessionService,
                $results['error'],
                NotificationType::ERROR,
                false
            );

            $this->redirect("/login");
        }
        
        if (isset($results['userdata']) && count($results['userdata']) === 1) {
            if ($results['userdata'][0]['active'] === 0) {        
                $this->setNotificationAndLoginStatus(
                    $notificationService, 
                    $sessionService,
                    'Użytkownik jest nieaktywny. Skontaktuj się z administratorem systemu',
                    NotificationType::WARNING,
                    false
                );
        
                $this->redirect("/login");
            }
            
            $authService->setUserLastLoginTime($results['userdata'][0]['email']);
            
            $this->setNotificationAndLoginStatus(
                $notificationService, 
                $sessionService,
                "Użytkownik pomyślnie zalogoway",
                NotificationType::INFO,
                true
            );
            
            $this->redirect("/admin");
        }

        $this->setNotificationAndLoginStatus(
            $notificationService, 
            $sessionService,
            'Zły login i/lub hasło. Spróbuj ponownie.',
            NotificationType::ERROR,
            false
        );

        $this->redirect("/login");
    }

    /**
     * @throws FrameworkException
     */
    #[Route('/api/logout', Http::GET)]
    public function logout(
        ServiceManager $serviceManager,
        array $params = []
    ): void {       
        $sessionService = $serviceManager->getService(SessionService::$name);
        if (!$sessionService instanceof SessionService) {
            throw new FrameworkException('Service error!', 'Session service not found');
        }
        
        $sessionService->sessionStart();

        $notificationService = $serviceManager->getService(NotificationService::$name);
        if (!$notificationService instanceof NotificationService) {
            throw new FrameworkException('Service error!', 'Notification service not found');
        }
        $this->setNotificationAndLoginStatus(
            $notificationService, 
            $sessionService,
            "Użytkownik wylogowany",
            NotificationType::INFO,
            false
        );

        $this->redirect("/");
    }
    
    private function setNotificationAndLoginStatus(
        NotificationService $notificationService,
        SessionService $sessionService,
        string $message,
        int $type,
        bool $logInUser = true
    ): void {
        $notificationService->addNotification('login', $message, $type);
        $notificationService->saveNotifications();
        $logInUser ? $sessionService->setLoginData() : $sessionService->unsetLoginData();
    }
}