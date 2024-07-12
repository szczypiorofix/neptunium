<?php

namespace Neptunium\Core;

use Exception;
use Neptunium\Controllers\HomeController;
use Neptunium\Controllers\MainController;

class Core {
    private Environment $environment;
    private Dotenv $dotenv;
    private Router $router;

    public function __construct(
        private readonly string $rootDir,
        private readonly string $appRootDir
    ) {
        $this->environment = new Environment($this->rootDir, $this->appRootDir);
        $this->dotenv = new Dotenv();
        try {
            $this->dotenv->load($this->rootDir . '/.env');
        } catch(Exception $exc) {
            var_dump($exc);
        }

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

        $urlPath = $this->parseUrl();
        $pageContent = $this->router->handleRoutes($urlPath);

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
}