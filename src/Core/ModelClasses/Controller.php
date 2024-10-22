<?php

namespace Neptunium\Core\ModelClasses;

abstract class Controller {
    public function redirect(string $url): void {
        header("Location: $url");
        exit();
    }
}
