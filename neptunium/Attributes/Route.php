<?php

namespace Neptunium\Attributes;

use Neptunium\Core\Http;

#[\Attribute]
class Route {
    public function __construct(public string $path, public string $method = Http::GET) {
//        echo '</br>registered path '. $path . '</br>';
    }
}