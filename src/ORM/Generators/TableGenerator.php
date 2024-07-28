<?php

namespace Neptunium\ORM\Generators;

use Neptunium\Core\DebugContainer;
use Neptunium\ModelClasses\Generator;
use Neptunium\ORM\Mapping\FieldPropertyType;
use Neptunium\ORM\Mapping\Table;
use ReflectionClass;
use ReflectionException;

class TableGenerator implements Generator {
    private Table $table;
    private string $tableName;
    private string $generateTableQuery;
    private string $originClass;
    private ReflectionClass $reflectionClassObject;

    public function __construct() {}

    public function generate(string $class): bool {
        $this->originClass = $class;
        try {
            $this->reflectionClassObject = $this->createReflectionObject();
            $this->generateTableObjectWithAttributes();
            $this->createTable();
        } catch (ReflectionException $e) {
            DebugContainer::$errors["ReflectionError"] = $e->getMessage();
            return false;
        }

        $this->show($this->tableName);

        return true;
    }

    /**
     * @throws ReflectionException
     */
    private function createReflectionObject(): ReflectionClass {
        return new ReflectionClass($this->originClass);
    }

    private function generateTableObjectWithAttributes(): void {
        $tableArrayColumns = [];
        $modelClassProperties = $this->reflectionClassObject->getProperties();
        $modelClassAttributes = $this->reflectionClassObject->getAttributes();
        foreach($modelClassProperties as $column) {
            $propertyAttributes = $column->getAttributes();
            $columnName = $column->getName();
            foreach($propertyAttributes as $attribute) {
                $tableArrayColumns[$columnName] = $attribute->getArguments();
            }
        }
        $this->tableName = $this->determineTableName($modelClassAttributes);
        $this->table = new Table($this->tableName);
        $this->table->setColumns($tableArrayColumns);
    }

    private function createTable(): void {
        $this->generateTableQuery = "CREATE TABLE IF NOT EXISTS `" .$this->tableName . "` (";

        $queryString = "";
        foreach($this->table->getColumns() as $columnName => $column) {
            $queryString .= "`" . $columnName . "`";
            $setLength = null;
            foreach($column as $attributeKey => $attributeValue) {
                switch($attributeKey) {
                    case 'type':
                        $queryString .= " ".FieldPropertyType::getLabel($attributeValue);
                        break;
                    case 'autoIncrement':
                        $queryString .= " AUTO_INCREMENT";
                        break;
                    case 'collation':
                        break;
                    case 'length':
                        break;
                    case 'nullable':
                        $queryString .= " NULL";
                        break;
                    case 'defaultValue':
                        break;
                    case 'unique':
                        break;
                    case 'primaryKey':
                        break;
                    case 'comment':
                        $queryString .= " COMMENT=`".$attributeValue."`";
                        break;
                    default:
                        break;
                }
            }
            $queryString .= ',';
        }

        // remove last ', ' part from query
        $queryString = rtrim($queryString, ", ");
        $this->generateTableQuery .= $queryString . ");";
        $this->show($this->generateTableQuery);

        // db -> run query
    }

    private function determineTableName(array $modelClassAttributes): string {
        $classNameArray = explode(DIRECTORY_SEPARATOR, $this->originClass);
        // Retrieve table name from class name
        $tableName = end($classNameArray);

        // check if there is an attribute for the model class
        if (isset($modelClassAttributes[0])) {
            $attributesValues = $modelClassAttributes[0]->getArguments();
            if (array_key_exists('name', $attributesValues)) {
                $tableName = $attributesValues['name'];
            }
        }
        return $tableName;
    }

    private function show(mixed $data): void {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }
}
