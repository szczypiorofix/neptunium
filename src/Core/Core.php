<?php

namespace Neptunium\Core;

use Neptunium\Config;
use Neptunium\Controllers\HomeController;
use Neptunium\Controllers\MainController;
use Neptunium\ModelClasses\Request;
use Neptunium\ModelClasses\Response;
use Neptunium\ORM\Generators\TableGenerator;
use Neptunium\ORM\Models\UserModel;

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

        $requiredEnvironmentalVariableKeys = Config::REQUIRED_ENVIRONMENTAL_VARIABLES;
        $allVariablesAreAvailable = $this->environment->checkRequiredEnvironmentalVariables($requiredEnvironmentalVariableKeys);
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
        if ($this->databaseConnection->db->isError()) {
            print_r($this->databaseConnection->db->getErrorMessage());
            return;
        }

        $tableGenerator = new TableGenerator();
        $success = $tableGenerator->generate(UserModel::class);
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
