<?php

namespace Neptunium\Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class HtmlView {
    const viewsPath = './src/views';
    const componentsPath = './src/views/components';

    private function __construct() {}
    private function __clone() {}

    public static function renderPage(string $view, array $params = []): string {
        return self::renderView(self::viewsPath, $view, $params);
    }

    public static function renderComponent(string $view, array $params = []): string {
        return self::renderView(self::componentsPath, $view, $params);
    }

    private static function renderView(string $path, string $view, array $params = []): string {
        $loader = new FilesystemLoader($path);
        $twig = new \Twig\Environment($loader, ['debug' => true]);
        try {
            $template = $twig->load($view);
        } catch (LoaderError|RuntimeError|SyntaxError $error) {
            return $error->getMessage();
        }
        $params['base_url'] = NEP_BASE_URL;
        $params['app_ver'] = NEP_APP_VER;
        $params['debugInfoData'] = DebugContainer::$info;
        $params['debugErrorData'] = DebugContainer::$error;
        return $template->render($params);
    }
}
