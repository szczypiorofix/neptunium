<?php

namespace Neptunium;

use Neptunium\Core\Core;

class App {
    private function __construct() {}
    private function __clone() {}

    public static function launch(string $rootDir): void {
        $core = new Core($rootDir, __DIR__);
    }
}
