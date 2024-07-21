<?php

namespace Neptunium\ORM\Mapping;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Table {
    public function __construct(public ?string $name) {}
}