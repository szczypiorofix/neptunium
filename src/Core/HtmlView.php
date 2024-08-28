<?php

namespace Neptunium\Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class HtmlView {
    private function __construct() {}
    private function __clone() {}

    public static function renderPage(string $view, array $params = []): string {
        $loader = new FilesystemLoader('./views');
        $twig = new \Twig\Environment($loader, ['debug' => true]);
        try {
            $template = $twig->load($view);
        } catch (LoaderError $e) {
            return $e->getMessage();
        } catch (RuntimeError $e) {
            return $e->getMessage();
        } catch (SyntaxError $e) {
            return $e->getMessage();
        }
        $params['base_url'] = NEP_BASE_URL;
        $params['app_ver'] = NEP_APP_VER;
        $params['debugInfoData'] = DebugContainer::$info;
        $params['debugErrorData'] = DebugContainer::$error;

        return $template->render($params);
    }

    public static function renderComponent(string $view, array $params = []): string {
        $loader = new FilesystemLoader('./views/components');
        $twig = new \Twig\Environment($loader, ['debug' => true]);
        try {
            $template = $twig->load($view);
        } catch (LoaderError $e) {
            return $e->getMessage();
        } catch (RuntimeError $e) {
            return $e->getMessage();
        } catch (SyntaxError $e) {
            return $e->getMessage();
        }
        $params['base_url'] = NEP_BASE_URL;
        $params['app_ver'] = NEP_APP_VER;
        $params['debugInfoData'] = DebugContainer::$info;
        $params['debugErrorData'] = DebugContainer::$error;

        return $template->render($params);
    }
}
