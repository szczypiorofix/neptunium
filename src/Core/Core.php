<?php

declare(strict_types=1);

namespace Neptunium\Core;

use Exception;
use Neptunium\Config;
use Neptunium\Controllers\AdminPageController;
use Neptunium\Controllers\ApiController;
use Neptunium\Controllers\LoginController;
use Neptunium\Controllers\MainController;
use Neptunium\Core\ModelClasses\NotificationType;
use Neptunium\Core\ModelClasses\Request;
use Neptunium\Core\ModelClasses\Response;
use Neptunium\Core\Services\AuthenticationService;
use Neptunium\Core\Services\NavigationService;
use Neptunium\Core\Services\NotificationService;
use Neptunium\Core\Services\SessionService;
use Neptunium\Middleware\HtmlContentMiddleware;
use ReflectionException;

class Core {
    private Environment $environment;
    private ?DatabaseConnection $databaseConnection = null;
    private Router $router;
    private Request $request;
    private Response $response;
    private string $pageContent = "";
    private ServiceManager $serviceManager;

    public function __construct(
        private readonly string $rootDir,
        private readonly string $appRootDir
    ) {}

    private function __clone() {}

    public function launch(): void {
        $this->prepareServices();
        $this->prepareEnvironment();
        $this->prepareDatabaseConnection();
        
        // set DB connection object to Auth service
        $authService = $this->serviceManager->getService('AuthService');
        if (!$authService instanceof AuthenticationService) {
            print_r("Błąd instancji klasy AuthenticationService");
            return;
        }
        if ($this->databaseConnection->getDatabase()->getPdo()) {
            $authService->setDatabaseConnection($this->databaseConnection);
        }

        /** Generate table... */
//        $tableGenerator = new TableGenerator();
//        if (!$tableGenerator->generate(UserServerModel::class, $this->databaseConnection)) {
//            print_r("Nie można wygenerować tabeli");
//        }

        $this->prepareRouter();
        $this->prepareRequestAndResponse();
        $this->handleRoutes();
        $this->prepareMiddlewares();
    }

    private function prepareServices(): void {
        $authService = new AuthenticationService();
        $sessionService = new SessionService();
        $notificationService = new NotificationService();
        $navigationService = new NavigationService();

        $this->serviceManager = new ServiceManager();
        $this->serviceManager->init(
            [
                AuthenticationService::$name    => $authService,
                SessionService::$name           => $sessionService,
                NotificationService::$name      => $notificationService,
                NavigationService::$name        => $navigationService,
            ]
        );
    }

    private function prepareEnvironment(): void {
        $this->environment = new Environment(
            $this->rootDir,
            $this->appRootDir
        );
        try {
            $this->environment->loadDotEnv($this->rootDir . '/.env');
        } catch (Exception $e) {
            echo 'An error occurred while loading environmental variables: '. $e->getMessage();
            exit();
        }

        $requiredEnvironmentalVariableKeys = Config::REQUIRED_ENVIRONMENTAL_VARIABLES;
        $allVariablesAreAvailable = $this->environment->checkRequiredEnvironmentalVariables($requiredEnvironmentalVariableKeys);
        if (!$allVariablesAreAvailable) {
            throw new ModelClasses\FrameworkException("Environmental Variables error!", "Not all environmental variables are available! Please check config file.");
        }
    }

    private function prepareRouter(): void {
        $this->router = new Router();
        try {
            $this->router->registerRoutesFromControllerAttributes([
                AdminPageController::class,
                MainController::class,
                ApiController::class,
                LoginController::class
            ]);
        } catch (ReflectionException $e) {
            echo 'An error occurred while registering routes: '. $e->getMessage();
        }
    }

    private function prepareDatabaseConnection(): void {
        $this->databaseConnection = DatabaseConnection::getConnection();
        if ($this->databaseConnection->getDatabase()->isError()) {
//            print_r($this->databaseConnection->getDatabase()->getErrorMessage());

            $notificationService = $this->serviceManager->getService('NotificationService');
            if ($notificationService instanceof NotificationService) {
                $notificationService->addNotification(
                    "Database connection error",
                    $this->databaseConnection->getDatabase()->getErrorMessage(),
                    NotificationType::ERROR
                );
                $notificationService->saveNotifications();
            }
        }
    }

    private function prepareRequestAndResponse(): void {
        $this->request = new Request();
        $this->response = new Response();
    }

    private function handleRoutes(): void {
        $this->pageContent = $this->router->handleRoutes(
            $this->request->getMethod(),
            $this->request->getUrl(),
            $this->serviceManager
        );
    }

    private function prepareMiddlewares(): void {
        $middleware = new HtmlContentMiddleware();
        $middleware->process(
            $this->request,
            $this->response,
            function() {
                $this->resolveResponse($this->pageContent);
            });
    }

    private function resolveResponse(string $pageContent): void {
        $this->response->setContent($pageContent);
        $this->response->viewPageContent();
    }
}
