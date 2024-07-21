<?php

namespace Neptunium\ORM\Generators;

use Neptunium\Core\DebugContainer;
use Neptunium\ModelClasses\Generator;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class TableGenerator implements Generator {
    private array $table;
    private mixed $originClass;

    public function __construct() {
        $this->table = [];
        $this->originClass = null;
    }

    public function generate(mixed $class): bool {
        $this->originClass = $class;
        try {
            $this->generateModelObjectWithAttributes();
        } catch (ReflectionException $e) {
            DebugContainer::$errors["ReflectionError"] = $e->getMessage();
            return false;
        }
        $this->show($this->table);
        return false;
    }

    /**
     * @throws ReflectionException
     */
    private function generateModelObjectWithAttributes(): void {
        $modelClassObject = new ReflectionClass($this->originClass);

        $modelClassProperties = $modelClassObject->getProperties();
        $modelClassAttributes = $modelClassObject->getAttributes();

        $tableName = $this->determineTableName($modelClassAttributes);

        $this->table = ['TABLE_NAME' => $tableName];
    }

    private function determineTableName(array $modelClassAttributes): string {
        $classNameArray = explode(DIRECTORY_SEPARATOR, $this->originClass);
        // Retrieve table name from class name
        $tableName = end($classNameArray);

        // check if there is an attribute for the model class
        if (isset($modelClassAttributes[0])) {
            $attributesValues = $modelClassAttributes[0]->getArguments();
            if (isset($attributesValues[0])) {
                $tableName = $attributesValues[0];
            }
        }
        return $tableName;
    }

    private function createTable(): bool {
        $arrayKeys = array_keys($this->table);
        if (isset($arrayKeys[0])) {
            $createTableQueryString = "CREATE TABLE IF NOT EXISTS `" .$arrayKeys[0] . "` (";
            foreach($this->table as $columns) {
                foreach($columns as $columnName => $columnAttributes) {
                    $createTableQueryString .= "`" . $columnName . "`";

                    $fieldType = null;
                    $fieldValue = null;
                    $fieldLength = null;
                    $isNullable = null;

                    foreach($columnAttributes as $columnAttributeName => $columnAttributeValue) {
                        if ($columnAttributeName === 'type') {
                            $fieldType = $columnAttributeValue->name;
                        } else {
                            $fieldValue = $columnAttributeValue;
                            if ($columnAttributeName === 'length') {
                                $fieldLength = $columnAttributeValue;
                            }
                            if ($columnAttributeName === 'nullable') {
                                $isNullable = $columnAttributeValue;
                            }
                        }
                    }

                    // TYPE
                    switch($fieldType) {
                        case 'Timestamp':
                            $createTableQueryString .= " TIMESTAMP ";
                            break;
                        case 'Integer':
                            $createTableQueryString .= " INT ";
                            break;
                        case 'Boolean':
                            $createTableQueryString .= " BOOL ";
                            break;
                        default:
                            if ($fieldLength) {
                                $createTableQueryString .= " VARCHAR($fieldLength) ";
                            } else {
                                $createTableQueryString .= " VARCHAR(21) ";
                            }
                            break;
                    }

                    // NULLABLE
                    if ($isNullable) {
                        $createTableQueryString .= " NULL ";
                    } else {
                        $createTableQueryString .= " NOT NULL ";
                    }

                    $createTableQueryString .= ',';
                }
            }
            $createTableQueryString .= ");";
            echo '<pre>';
            var_dump($this->table);
            echo '</pre>';
        }
        return false;
    }

    private function show(mixed $data): void {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }
}
