<?php

namespace Neptunium\Services;

use Neptunium\Attributes\Route;
use Neptunium\ModelClasses\BaseService;
use Neptunium\ModelClasses\Request;

class NavigationService extends BaseService {

    /**
     * @var Route[]
     */
    private array $availableRoutes;

    public function __construct() {
        parent::__construct('navigation');
    }

    public function initialize(): void {
        $this->availableRoutes = [];
    }

    /**
     * @param Route[] $routes
     * @return void
     */
    public function setAvailableRoutes(array $routes): void {
        $this->availableRoutes = $routes;
    }

    public function prepareNavigationBar(string $currentPath, bool $userLoggedIn = false): array {
        return match ($currentPath) {
            'home' => [
                'main page' => [
                    'title' => 'Naptunium',
                    'path' => '/'
                ],
                'login page' => [
                    'title' => $userLoggedIn ? 'Logout' : 'Login',
                    'path' => $userLoggedIn ? '/api/logout' : '/login'
                ]
            ],
            'login' => [
                'home page' => [
                    'title' => 'Home',
                    'path' => '/home'
                ],
            ],
            'main' => [
                'home page' => [
                    'title' => 'Home',
                    'path' => '/home'
                ],
                'login page' => [
                    $userLoggedIn ?? 'title' => 'Logout',
                    $userLoggedIn ?? 'path' => '/api/logout',
                ]
            ],
            default => [
                'home' => [
                    'title' => 'Home',
                    'path' => '/home'
                ]
            ],
        };
    }

    /**
     * @return Route[] get routes
     */
    public function getAvailableRoutes(): array {
        return $this->availableRoutes;
    }
}
