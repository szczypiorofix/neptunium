<?php

namespace Neptunium\ORM\Generators;

use Neptunium\Core\DebugContainer;
use Neptunium\ModelClasses\Generator;
use ReflectionClass;
use ReflectionException;

class TableGenerator implements Generator {

    public function generate(mixed $class): bool {
        try {
            $this->generateModelObjectWithAttributes($class);
        } catch (ReflectionException $e) {
            DebugContainer::$errors["ReflectionError"] = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * @throws ReflectionException
     */
    private function generateModelObjectWithAttributes(mixed $class): void {
//        throw new ReflectionException("Simple error");
        $reflection = new ReflectionClass($class);
        $reflectionProperties = $reflection->getProperties();
        $reflectionAttributes = $reflection->getAttributes();
        $inst = $reflectionAttributes[0]->newInstance();
        foreach ($reflectionProperties as $reflectionProperty) {
            $typeAttributes = $reflectionProperty->getAttributes();
            $typeClass = $reflectionProperty->getName();
            foreach($typeAttributes as $typeAttribute) {
                $attribute = $typeAttribute->newInstance();
                DebugContainer::$info['UserTableGenerate'][$inst->name][$typeClass] = $attribute;
            }
        }
    }
}
