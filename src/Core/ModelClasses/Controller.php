<?php

namespace Neptunium\Core\ModelClasses;

abstract class Controller {
    public function redirect(string $path): void {
        $baseUrl = getenv("NEP_BASE_URL");
        $url = trim($baseUrl, '/') . '/' . trim($path, '/'); 
        
        header("Location: $url");
        exit();
    }
}
