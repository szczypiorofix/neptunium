<?php

namespace Neptunium\Core;

use Neptunium\Controllers\HomeController;
use Neptunium\Controllers\MainController;

class Core {
    private ?Environment $environment;
    private Dotenv $dotenv;
    private Router $router;

    public function __construct(
        private readonly string $rootDir,
        private readonly string $appRootDir
    ) {}

    private function __clone() {}

    public function launch(): void {
        $this->environment = new Environment(
            $this->rootDir,
            $this->appRootDir,
            [
                "DB_NAME",
                "DB_HOST",
                "DB_USER",
                "DB_PASS",
            ]
        );

        try {
            $this->environment->loadDotEnv();
        } catch (\Exception $e) {
            echo 'An error occurred while loading environmental variables: '. $e->getMessage();
        }

        $db = DatabaseConnection::getConnection();

        Registry::add($db, "database connection");
        Registry::add($this->environment, "Environment");

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

        Registry::add($this->router, "Router");
        Registry::remove("Environment");

        $urlPath = $this->parseUrl();
        $pageContent = $this->router->handleRoutes($urlPath);

//        $this->dump(Registry::returnList());

        echo $pageContent;
    }

    public function getRouter(): Router {
        return $this->router;
    }

    public function getEnvironment(): Environment {
        return $this->environment;
    }

    private function parseUrl(): string {
        $filteredUrl = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);
        if ($filteredUrl) {
            return rtrim($filteredUrl);
        }
        return '';
    }

    private function dump(mixed $data): void {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}