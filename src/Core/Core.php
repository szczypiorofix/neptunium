<?php

declare(strict_types=1);

namespace Neptunium\Core;

use Neptunium\Config;
use Neptunium\Controllers\ApiController;
use Neptunium\Controllers\HomeController;
use Neptunium\Controllers\MainController;
use Neptunium\Middleware\HtmlContentMiddleware;
use Neptunium\ModelClasses\Request;
use Neptunium\ModelClasses\Response;

class Core {
    private Environment $environment;
    private DatabaseConnection $databaseConnection;
    private Router $router;
    private Request $request;
    private Response $response;

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
        $this->prepareRouter();
        $this->prepareRequestAndResponse();

        $pageContent = $this->handleRoutes();

        $middleware = new HtmlContentMiddleware();
        $middleware->process(
            $this->request,
            $this->response,
            function() use ($pageContent) {
                $this->resolveResponse($pageContent);
            });
    }

    private function prepareServices(): void {
        $this->serviceManager = new ServiceManager();
        $this->serviceManager->init();;
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
                    HomeController::class,
                    MainController::class,
                    ApiController::class,
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

    private function handleRoutes(): string {
        return $this->router->handleRoutes(
            $this->request->getMethod(),
            $this->request->getUrl()
        );
    }

    private function resolveResponse(string $pageContent): void {
        $this->response->setContent($pageContent);
        $this->response->viewPageContent();
    }
}
