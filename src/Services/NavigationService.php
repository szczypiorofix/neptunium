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
        switch ($currentPath) {
            case 'home':
                return [
                    'main page' => [
                        'title' => 'Naptunium',
                        'path' => '/'
                    ],
                    'login page' => [
                        'title' => $userLoggedIn ? 'Logout' : 'Login',
                        'path' => $userLoggedIn ? '/api/logout' : '/login'
                    ]
                ];
            case 'login':
                return [
                    'home page' => [
                        'title' => 'Home',
                        'path' => '/home'
                    ],
                ];
            case 'main':
                $nav = [
                    'home page' => [
                        'title' => 'Home',
                        'path' => '/home'
                    ],
                ];
                if ($userLoggedIn) {
                    $nav['login page'] = [
                        'title' => 'Logout',
                        'path' => '/api/logout',
                    ];
                }
                return $nav;

            case '404':
                return [
                    'main page' => [
                        'title' => 'Naptunium',
                        'path' => '/'
                    ],
                    'home page' => [
                        'title' => 'Home',
                        'path' => '/home'
                    ],
                ];

            default:
                return [
                    'defau' => [
                        'title' => 'Home',
                        'path' => '/home'
                    ]
                ];
        }
    }

    /**
     * @return Route[] get routes
     */
    public function getAvailableRoutes(): array {
        return $this->availableRoutes;
    }
}
