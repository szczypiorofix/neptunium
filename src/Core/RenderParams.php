<?php

namespace Neptunium\Core;

use Neptunium\Core\ModelClasses\FrameworkException;
use ReflectionClass;

class RenderParams {
    public static string $templateFileName = '';
    public static string $templateName = '';
    public static array $queryData = [];
    public static array $sessionData = [];
    public static array $navigationData = [];
    public static array $notifications = [];
    public static string $loginData = '';
    public static string $baseUrl = '';
    public static string $appVer = '';
    public static array $debugInfoData = [];
    public static array $debugWarningData = [];
    public static array $debugErrorData = [];
    public static array $serverList = [];

    public static function getAll(): array {
        $allProperties = array();
        $class = new ReflectionClass(self::class);
        
        $classProperties = $class->getProperties();
        foreach($classProperties as $property) {
            $name = $property->getName();
            $allProperties[$name] = $class->getStaticPropertyValue($name);
        }
        return $allProperties;
    }

    /**
     * @throws FrameworkException
     */
    public static function set(array $params): void {
        foreach($params as $paramKey => $paramValue) {
            if (!property_exists(self::class, $paramKey)) {
                throw new FrameworkException("Brak własności w klasie!", "Brak własności ".$paramKey." w klasie ".self::class);
            }
            RenderParams::${$paramKey} = $paramValue;
        }
    }
}
