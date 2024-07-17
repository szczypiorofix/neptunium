<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\View;
use Neptunium\ModelClasses\Controller;
use Neptunium\ModelClasses\Http;


class HomeController extends Controller {
    #[Route('/home', Http::GET)]
    public function index(array $params = []): string {
        return View::render('home.twig',
            [
                'data' => $params
            ]
        );
    }
}
