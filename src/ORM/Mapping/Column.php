<?php

namespace Neptunium\ORM\Mapping;

#[\Attribute]
class Column {
    public function __construct(
        public FieldPropertyType $type = FieldPropertyType::VarChar,
        public int $length = 256,
        public string $collation = 'utf8mb4_general_ci',
        public mixed $defaultValue = null,
        public bool $primaryKey = false,
        public bool $nullable = false,
        public bool $unique = false,
        public bool $autoIncrement = false,
        public string $comment = "",
    ) {}
}
