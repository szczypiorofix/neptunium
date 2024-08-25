<?php

namespace Neptunium\ModelClasses;

class Notification {
    public function __construct(public string $text, public int $type) {}
}
