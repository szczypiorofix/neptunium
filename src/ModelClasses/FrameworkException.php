<?php

namespace Neptunium\ModelClasses;

use Exception;

class FrameworkException extends Exception {
    public function __construct(
        public string $title,
        string $message,
        int $code = 0,
        Exception $previousException = null
    ) {
        parent::__construct($message, $code, $previousException);
    }

    public function __toString(): string {
        return __CLASS__ . ": [{$this->code}]: {$this->title} - {$this->message}\n";
    }
}
