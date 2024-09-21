<?php

namespace Neptunium\Middleware;

use Neptunium\Core\ModelClasses\Middleware;
use Neptunium\Core\ModelClasses\Request;
use Neptunium\Core\ModelClasses\Response;

class HtmlContentMiddleware extends Middleware {
    public function process(Request $request, Response $response, callable $next): void {
        $response->setHeaders(
            [
                "Content-Type" => "text/html; charset=utf-8"
            ]
        );
        $next($response);
    }
}