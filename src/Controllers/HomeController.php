<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\HtmlView;
use Neptunium\ModelClasses\Controller;
use Neptunium\ModelClasses\Http;


class HomeController extends Controller {
    #[Route('/home', Http::GET)]
    public function index(array $params = []): string {
        return HtmlView::renderPage('index.twig',
            [
                'templateFileName' => 'home.twig',
                'templateName' => 'home',
                'queryData' => $params,
            ]
        );
    }
}
