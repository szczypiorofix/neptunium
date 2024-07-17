<?php

namespace Middleware;

use ModelClasses\Middleware;
use ModelClasses\Request;
use ModelClasses\Response;

class HtmlContentMiddleware extends Middleware {
    public function process(Request $request, Response $response, callable $next) {
        // modify response headers - set content type to text/html
    }
}