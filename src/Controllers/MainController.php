<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\View;
use Neptunium\ModelClasses\Controller;
use Neptunium\ModelClasses\Http;

class MainController extends Controller {
    #[Route('/', Http::GET)]
    public function index(): string {
        return View::render('index.twig', [
            'data' => 'Page content...'
        ]);
    }
}
