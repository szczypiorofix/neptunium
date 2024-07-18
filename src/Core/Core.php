<?php

namespace Neptunium\Core;

use Neptunium\Controllers\HomeController;
use Neptunium\Controllers\MainController;
use Neptunium\ModelClasses\Request;
use Neptunium\ModelClasses\Response;

class Core {
    private Environment $environment;
    private DatabaseConnection $databaseConnection;
    private Router $router;
    private Request $request;
    private Response $response;

    public function __construct(
        private readonly string $rootDir,
        private readonly string $appRootDir
    ) {}

    private function __clone() {}

    public function launch(): void {
        $this->prepareEnvironment();
        $this->prepareDatabaseConnection();
        $this->prepareRouter();
        $this->resolveRequest();

        $pageContent = $this->handleRoutes();

        $this->resolveResponse($pageContent);
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
        }
        $requiredEnvironmentalVariableKeys = [
            "DB_NAME",
            "DB_HOST",
            "DB_USER",
            "DB_PASS",
        ];
        try {
            $allVariablesAreAvailable = $this->environment->checkRequiredEnvironmentalVariables($requiredEnvironmentalVariableKeys);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    private function prepareRouter(): void {
        $this->router = new Router();
        try {
            $this->router->registerRoutesFromControllerAttributes(
                [
                    HomeController::class,
                    MainController::class
                ]
            );
        } catch (\ReflectionException $e) {
            echo 'An error occurred while registering routes: '. $e->getMessage();
        }
    }

    private function prepareDatabaseConnection(): void {
        $this->databaseConnection = DatabaseConnection::getConnection();
    }

    private function resolveRequest(): void {
        $this->request = new Request();
    }

    private function handleRoutes(): string {
        return $this->router->handleRoutes(
            $this->request->getMethod(),
            $this->request->getUrl()
        );
    }

    private function resolveResponse(string $pageContent): void {
        $this->response = new Response();
        $this->response->setContent($pageContent);
        $this->response->viewPageContent();
    }

    private function dump(mixed $data, string $name = ''): void {
        if ($name) {
            echo "<p>$name</p>";
        }
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
