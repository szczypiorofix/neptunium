<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\DebugContainer;
use Neptunium\Core\View;
use Neptunium\ModelClasses\Controller;
use Neptunium\ModelClasses\Http;

class MainController extends Controller {
    #[Route('/', Http::GET)]
    public function index(array $params = []): string {
        return View::render('index.twig',
            [
                'templateFileName' => 'main.twig',
                'templateName' => 'main',
                'debugData'=> DebugContainer::$info,
                'queryData' => $params,
            ]
        );
    }
}
