<?php

namespace Controllers;

use Attributes\Route;
use Core\View;
use ModelClasses\Controller;
use ModelClasses\Http;

class MainController extends Controller {
    #[Route('/', Http::GET)]
    public function index(): string {
        return View::render('index.twig', [
            'data' => 'Page content...'
        ]);
    }
}
