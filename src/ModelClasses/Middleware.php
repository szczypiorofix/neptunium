<?php

namespace Neptunium\ModelClasses;

abstract class Middleware {
    abstract public function process(Request $request, Response $response, callable $next);
}
