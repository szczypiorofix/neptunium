<?php

namespace Neptunium\Core\Services;

use Neptunium\Core\Attributes\Route;
use Neptunium\Core\ModelClasses\BaseService;

class NavigationService extends BaseService {
    public static string $name = 'NavigationService';
    /**
     * @var Route[]
     */
    private array $availableRoutes;

    public function __construct() {
        parent::__construct(self::$name);
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
        $nav = array(
            'login_page' => [
                'title' => 'Login',
                'path' => '/login'
            ]
        );
        if ($userLoggedIn) {
            $nav = array(
                'admin_page' => [
                    'title' => 'Admin',
                    'path' => '/admin'
                ],
                'login_page' => [
                    'title' => 'Logout',
                    'path' => '/api/logout'
                ]
            );
        }

        switch ($currentPath) {
            case 'home':
                return [
                    'main_page' => [
                        'title' => 'Naptunium',
                        'path' => '/'
                    ],
                    'login_page' => [
                        'title' => $userLoggedIn ? 'Logout' : 'Login',
                        'path' => $userLoggedIn ? '/api/logout' : '/login'
                    ]
                ];

            case 'login':
                return [
                    'main page' => [
                        'title' => 'Naptunium',
                        'path' => '/'
                    ],
                ];

            default:
                return $nav;
        }
    }

    /**
     * @return Route[] get routes
     */
    public function getAvailableRoutes(): array {
        return $this->availableRoutes;
    }
}
