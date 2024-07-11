<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\Controller;
use Neptunium\Core\Http;
use Neptunium\Core\View;

class MainController extends Controller {
    #[Route('/', Http::GET)]
    public function index(): string {
        return View::render('index.twig', [
            'data' => 'Page content...'
        ]);
    }
}