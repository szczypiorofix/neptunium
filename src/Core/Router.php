<?php

namespace Neptunium\Core;

use Neptunium\Attributes\Route;
use ReflectionException;

/**
 * Route format:
 * [
 *  [request method]  => [
 *      [path] => [class, class method]
 *      ]
 * ]
 */
class Router {
    private array $routes = [];

    public function __construct() {}

    /**
     * @throws ReflectionException
     */
    public function registerRoutesFromControllerAttributes(array $controllers): void {
        foreach($controllers as $controller) {
            $reflectionController = new \ReflectionClass($controller);
            foreach($reflectionController->getMethods() as $method) {
                $attributes = $method->getAttributes(Route::class);
                foreach($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $this->register($route->method, $route->path, [$controller, $method->getName()]) ;
                }
            }
        }
    }

    public function handleRoutes(string $requestMethod, string $requestUrl): string {
        foreach($this->routes as $routeRequestMethodKey => $routeRequestMethodValue) {
            if ($requestMethod === $routeRequestMethodKey) {
                foreach($routeRequestMethodValue as $routeUrl => $routeValue) {
                    $routeUrlParsed = trim($routeUrl, '/');
                    $parsedUrl = trim($requestUrl, '/');
                    $requestQueryString = $this->retrieveGetParameters();
                    if ($routeUrlParsed === $parsedUrl) {
                        $className = $routeValue[0];
                        $classObject = new $className();
                        return call_user_func_array(array($classObject, $routeValue[1]), [$requestQueryString]);
                    }
                }
            }
        }
        http_response_code(404);
        return View::render('404.twig');
    }

    public function register(
        string $requestMethod,
        string $route,
        callable | array $action
    ) : self {
        $this->routes[$requestMethod][$route] = $action;
        return $this;
    }

    public function get(string $route, callable | array $action) : self {
        return $this->register('GET', $route, $action);
    }

    public function getRoutes(): array {
        return $this->routes;
    }

    private function retrieveGetParameters(): array|false|int|null|string {
        $actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $queryString = parse_url($actual_link, PHP_URL_QUERY);
        $queryStringParameters = [];
        parse_str($queryString, $queryStringParameters);
        return $queryStringParameters;
    }
}
