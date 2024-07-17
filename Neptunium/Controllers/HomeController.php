<?php

namespace Controllers;

use Attributes\Route;
use Core\View;
use ModelClasses\Controller;
use ModelClasses\Http;


class HomeController extends Controller {
    #[Route('/home', Http::GET)]
    public function index(): string {
        return View::render('home.twig');
    }
}
