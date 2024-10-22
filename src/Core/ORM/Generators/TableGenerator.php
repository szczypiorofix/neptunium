<?php

namespace Neptunium\Core\ORM\Generators;

use Neptunium\Core\DatabaseConnection;
use Neptunium\Core\DebugContainer;
use Neptunium\Core\Interfaces\Generator;
use Neptunium\Core\ORM\Mapping\FieldPropertyType;
use Neptunium\Core\ORM\Mapping\Table;
use ReflectionClass;
use ReflectionException;

class TableGenerator implements Generator {
    private Table $table;
    private string $tableName;
    private string $generateTableQuery;
    private string $originClass;
    private DatabaseConnection $databaseConnection;
    private ReflectionClass $reflectionClassObject;

    public function __construct() {}

    public function generate(string $class, DatabaseConnection $databaseConnection): bool {
        $this->originClass = $class;
        $this->databaseConnection = $databaseConnection;
        try {
            $this->reflectionClassObject = $this->createReflectionObject();
            $this->generateTableObjectWithAttributes();
            $this->createTable();
        } catch (ReflectionException $e) {
            DebugContainer::$error["ReflectionError"] = $e->getMessage();
            return false;
        }
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
        $tableComment = "";
        $tableCollation = "";
        $this->table = new Table($this->tableName, $tableComment, $tableCollation);
        $this->table->setColumns($tableArrayColumns);
    }

    private function createTable(): void {
        $this->generateTableQuery = "CREATE TABLE IF NOT EXISTS `" .$this->tableName . "` (";

        $queryString = "";
        foreach($this->table->getColumns() as $columnName => $column) {
            $queryString .= "`" . $columnName . "`";
            $attributeLength = 0;
            foreach($column as $attributeKey => $attributeValue) {
                if ($attributeKey === 'length') {
                    $attributeLength = $attributeValue;
                }
            }
            foreach($column as $attributeKey => $attributeValue) {
                switch($attributeKey) {
                    case 'type':
                        $attributeType = FieldPropertyType::getLabel($attributeValue);
                        if (str_starts_with($attributeType, 'VARCHAR') && $attributeLength > 0) {
                            $queryString .= " ".$attributeType."($attributeLength)";
                            break;
                        }
                        $queryString .= " ".$attributeType;
                        break;
                    case 'nullable':
                        $queryString .= " NULL";
                        break;
                    case 'autoIncrement':
                        $queryString .= " AUTO_INCREMENT";
                        break;
                    case 'collation':
                        $queryString .= " COLLATION ".$attributeValue;
                        break;
                    case 'defaultValue':
                        $queryString .= " DEFAULT ".$attributeValue;
                        break;
                    case 'unique':
                        $queryString .= " UNIQUE";
                        break;
                    case 'primaryKey':
                        $queryString .= " PRIMARY KEY";
                        break;
                    case 'comment':
                        $queryString .= " COMMENT '".$attributeValue."'";
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

        $this->databaseConnection->getDatabase()->getPdo()->exec($this->generateTableQuery);
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
