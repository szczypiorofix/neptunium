<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\DebugContainer;
use Neptunium\Core\HtmlView;
use Neptunium\ModelClasses\Controller;
use Neptunium\ModelClasses\Http;

class LoginController extends Controller {
    #[Route('/login', Http::GET)]
    public function index(array $params = []): string {
        return HtmlView::renderPage('index.twig',
            [
                'templateFileName' => 'login.twig',
                'templateName' => 'login',
                'debugInfoData'=> DebugContainer::$info,
                'debugErrorData'=> DebugContainer::$errors,
                'queryData' => $params,
            ]
        );
    }
}