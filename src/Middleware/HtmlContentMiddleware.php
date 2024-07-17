<?php

namespace Neptunium\Middleware;

use Neptunium\ModelClasses\Middleware;
use Neptunium\ModelClasses\Request;
use Neptunium\ModelClasses\Response;

class HtmlContentMiddleware extends Middleware {
    public function process(Request $request, Response $response, callable $next) {
        // modify response headers - set content type to text/html
    }
}