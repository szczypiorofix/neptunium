<?php

declare(strict_types=1);

namespace Neptunium\Core;

use Neptunium\Config;
use Neptunium\Controllers\AdminPageController;
use Neptunium\Controllers\ApiController;
use Neptunium\Controllers\LoginController;
use Neptunium\Controllers\MainController;
use Neptunium\Core\ModelClasses\Request;
use Neptunium\Core\ModelClasses\Response;
use Neptunium\Core\Services\AuthenticationService;
use Neptunium\Core\Services\NavigationService;
use Neptunium\Core\Services\NotificationService;
use Neptunium\Core\Services\SessionService;
use Neptunium\Middleware\HtmlContentMiddleware;

class Core {
    private Environment $environment;
    private DatabaseConnection $databaseConnection;
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
        $this->serviceManager->getAuthenticationService()->setDatabaseConnection($this->databaseConnection);

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

        $this->serviceManager = ServiceManager::getInstance();
        $this->serviceManager->init(
            $authService,
            $sessionService,
            $notificationService,
            $navigationService,
        );
    }

    private function prepareEnvironment(): void {
        $this->environment = new Environment(
            $this->rootDir,
            $this->appRootDir
        );
        try {
            $this->environment->loadDotEnv($this->rootDir . '/.env');
        } catch (\Exception $e) {
            echo 'An error occurred while loading environmental variables: '. $e->getMessage();
            exit();
        }

        define('NEP_BASE_URL', getenv(Config::ENV_NEP_BASE_URL));
        define('NEP_APP_VER', '1.0.1');

        $requiredEnvironmentalVariableKeys = Config::REQUIRED_ENVIRONMENTAL_VARIABLES;
        $allVariablesAreAvailable = $this->environment->checkRequiredEnvironmentalVariables($requiredEnvironmentalVariableKeys);
    }

    private function prepareRouter(): void {
        $this->router = new Router();
        try {
            $this->router->registerRoutesFromControllerAttributes(
                [
                    AdminPageController::class,
                    MainController::class,
                    ApiController::class,
                    LoginController::class
                ]
            );
        } catch (\ReflectionException $e) {
            echo 'An error occurred while registering routes: '. $e->getMessage();
        }
    }

    private function prepareDatabaseConnection(): void {
        $this->databaseConnection = DatabaseConnection::getConnection();
        if ($this->databaseConnection->getDatabase()->isError()) {
            print_r($this->databaseConnection->getDatabase()->getErrorMessage());
        }
    }

    private function prepareRequestAndResponse(): void {
        $this->request = new Request();
        $this->response = new Response();
    }

    private function handleRoutes(): void {
        $this->pageContent = $this->router->handleRoutes(
            $this->request->getMethod(),
            $this->request->getUrl()
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
