<?php

namespace Neptunium\Controllers;

use Neptunium\Attributes\Route;
use Neptunium\Core\Controller;
use Neptunium\Core\Http;
use Neptunium\Core\View;


class HomeController extends Controller {
    #[Route('/home', Http::GET)]
    public function index(): string {
        return View::render('home.twig');
    }
}
