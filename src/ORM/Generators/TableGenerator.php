<?php

namespace Neptunium\ORM\Generators;

use Neptunium\Core\DebugContainer;
use Neptunium\ModelClasses\Generator;
use ReflectionClass;
use ReflectionException;

class TableGenerator implements Generator {

    /**
     * @throws ReflectionException
     */
    public function generate(mixed $class): bool {
        $reflection = new ReflectionClass($class);
        $reflectionProperties = $reflection->getProperties();
        $reflectionAttributes = $reflection->getAttributes();
        $inst = $reflectionAttributes[0]->newInstance();
        foreach ($reflectionProperties as $reflectionProperty) {
            $typeAttributes = $reflectionProperty->getAttributes();
            $typeClass = $reflectionProperty->getName();
            foreach($typeAttributes as $typeAttribute) {
                $attribute = $typeAttribute->newInstance();
//                DebugContainer::$info['UserTableGenerate'][$inst->name][$typeClass] = $attribute;
            }
        }
        return true;
    }
}
