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

        $newInstance = $reflection->newInstance();
        foreach ($reflectionProperties as $reflectionProperty) {
//            $newInstance->{$reflectionProperty->getName()} = $class->{$reflectionProperty->getName()};

            $typeAttributes = $reflectionProperty->getAttributes();
            foreach($typeAttributes as $typeAttribute) {
                $attribute = $typeAttribute->newInstance();

                DebugContainer::$info['UserTableGenerate'][] = $attribute;
            }
        }
        return true;
    }
}
