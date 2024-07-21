<?php

namespace Neptunium\ORM\Mapping;

#[\Attribute]
class Table {
    public function __construct(public string $name) {}
}