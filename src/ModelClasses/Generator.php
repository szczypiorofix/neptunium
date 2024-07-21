<?php

namespace Neptunium\ModelClasses;

interface Generator {
    public function generate(mixed $class): bool;
}
