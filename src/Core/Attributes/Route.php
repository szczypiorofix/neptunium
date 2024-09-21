<?php

namespace Neptunium\Core\Attributes;

use Neptunium\Core\ModelClasses\Http;

#[\Attribute]
class Route {
    public function __construct(public string $path, public string $method = Http::GET) {}
}
