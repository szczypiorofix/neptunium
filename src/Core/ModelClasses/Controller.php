<?php

namespace Neptunium\Core\ModelClasses;

use JetBrains\PhpStorm\NoReturn;

abstract class Controller {
    #[NoReturn]
    public function redirect(string $url): void {
        header("Location: $url");
        exit();
    }
}
