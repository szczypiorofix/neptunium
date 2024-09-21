<?php

namespace Neptunium\Core\ModelClasses;

abstract class Middleware {
    abstract public function process(Request $request, Response $response, callable $next);
}
