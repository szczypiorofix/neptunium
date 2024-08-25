<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\DebugContainer;
use Neptunium\Core\HtmlView;
use Neptunium\ModelClasses\Controller;
use Neptunium\ModelClasses\Http;

class MainController extends Controller {
    #[Route('/', Http::GET)]
    public function index(array $params = []): string {
        session_start();
        return HtmlView::renderPage('index.twig',
            [
                'templateFileName'  => 'main.twig',
                'templateName'      => 'main',
                'debugInfoData'     => DebugContainer::$info,
                'debugErrorData'    => DebugContainer::$error,
                'queryData'         => $params,
                'sessionData'       => $_SESSION ?? [],
            ]
        );
    }
}
