<?php

namespace Neptunium\ModelClasses;

use JetBrains\PhpStorm\NoReturn;

abstract class Controller {
    public abstract function index(array $params = []): string;

    #[NoReturn]
    public function redirect(string $url): void {
        header("Location: $url");
        exit();
    }
}
