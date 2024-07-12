<?php

namespace Neptunium\Core;

use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;

class View {
    private function __construct() {}
    private function __clone() {}

    public static function render(string $view, array $params = []): string {
        $loader = new FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, ['debug' => true]);
        try {
            $template = $twig->load($view);
        } catch (LoaderError $e) {
            return $e->getMessage();
        }
        return $template->render($params);
    }
}
