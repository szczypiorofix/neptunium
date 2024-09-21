<?php

namespace Neptunium\Core\ModelClasses;

class Notification {
    public function __construct(public string $text, public int $type) {}
}
