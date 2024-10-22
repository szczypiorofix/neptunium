<?php

namespace Neptunium\Core\Attributes;

use Neptunium\Core\ModelClasses\Http;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Route {
    public function __construct(public string $path, public string $method = Http::GET) {}
}
