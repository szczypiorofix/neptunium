<?php

namespace Neptunium\Core;

use Neptunium\Config;
use Neptunium\Controllers\HomeController;
use Neptunium\Controllers\MainController;
use Neptunium\ModelClasses\Request;
use Neptunium\ModelClasses\Response;
use Neptunium\ORM\Generators\TableGenerator;
use Neptunium\Middleware\HtmlContentMiddleware;
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
        $this->prepareRequestAndResponse();

        $pageContent = $this->handleRoutes();

        $middleware = new HtmlContentMiddleware();
        $middleware->process(
            $this->request,
            $this->response,
            function () use ($pageContent) {
                $this->resolveResponse($pageContent);
            });
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
        if ($this->databaseConnection->getDatabase()->isError()) {
            print_r($this->databaseConnection->getDatabase()->getErrorMessage());
            return;
        }

        // ======= Temporary disabled
        // $tableGenerator = new TableGenerator();
        // $success = $tableGenerator->generate(UserModel::class, $this->databaseConnection);
        // ========


//        $userOne = new UserModel();
//        $userOne->username = 'UserOnee';
//        $userOne->password = 'xxxxxxx';
//        $userOne->email = 'aaa@bbb.cc';
//        $userOne->active = false;
//        $userOne->firstName = "UserOne First Name";
//        $userOne->lastName = "UserOne Last Name";

//        print_r(['result' => $userOne->add($this->databaseConnection)]);

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
