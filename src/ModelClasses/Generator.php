<?php

namespace Neptunium\ModelClasses;

interface Generator {
    public function generate(string $class): bool;
}
