<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\HtmlView;
use Neptunium\ModelClasses\Controller;
use Neptunium\ModelClasses\Http;

class ApiController extends Controller {
    #[Route('/api', Http::GET)]
    function index(array $params = []): string {
        return HtmlView::renderPage('index.twig',
            [
                'templateFileName' => 'api.twig',
                'templateName' => 'api',
                'queryData' => $params,
            ]
        );
    }
}