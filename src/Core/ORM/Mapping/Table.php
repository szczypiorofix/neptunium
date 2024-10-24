<?php

namespace Neptunium\Core\ORM\Mapping;

use ReflectionProperty;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Table {
    private array $columns;
    private string $createTableQuery;

    public function __construct(
        private ?string $name,
        private ?string $comment,
        private ?string $collate
    ) {
        $this->createTableQuery = "";
        $this->columns = [];
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): void {
        $this->name = $name;
    }

    public function getCreateTableQuery(): string {
        return $this->createTableQuery;
    }

    public function getComment(): ?string {
        return $this->comment;
    }

    public function setComment(?string $comment): void {
        $this->comment = $comment;
    }

    public function getCollate(): ?string {
        return $this->collate;
    }

    public function setCollate(?string $collate): void {
        $this->collate = $collate;
    }

    public function setCreateTableQuery(string $createTableQuery): void {
        $this->createTableQuery = $createTableQuery;
    }

    /**
     * Returns a list of Personality objects
     * @return ReflectionProperty[]
     */
    public function getColumns(): array {
        return $this->columns;
    }

    /**
     * @param ReflectionProperty[] $columns
     * @return void
     */
    public function setColumns(array $columns): void {
        $this->columns = $columns;
    }
}
