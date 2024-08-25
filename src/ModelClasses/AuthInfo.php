<?php

namespace Neptunium\ModelClasses;

class AuthInfo {
    public string $message;
    public bool $error;
    public int $code;

    public function __construct(string $message = '', bool $error = false, int $code = 200) {
        $this->message = $message;
        $this->error = $error;
        $this->code = $code;
    }
}
