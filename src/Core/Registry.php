<?php

namespace Neptunium\Core;

class Registry
{
    private static array $store = array();

    private function __construct()
    {
    }
    private function __clone()
    {
    }

    public static function add(
        object $object,
        $name = null
    ): mixed {
        $n = (!is_null($name)) ? $name : get_class($object);
        if (isset(self::$store[$n])) {
            return self::$store[$n];
        }
        self::$store[$n] = $object;
        return null;
    }

    public static function getSize(): int
    {
        return count(self::$store);
    }

    public static function showList(): void
    {
        $r = "";
        foreach (self::$store as $s) {
            $r .= $s . '<br>';
        }
        echo $r;
    }

    public static function returnList(): array
    {
        return self::$store;
    }

    public static function get(string $name): mixed
    {
//        if (!self::contains($name)) {
//            throw new FrameworkException('Brak obiektu!', "Nie znaleziono obiektu ".$name);
//        }
        return self::$store[$name];
    }

    public static function remove(string $name): void
    {
//        if (!self::contains($name)) {
//            throw new FrameworkException(
//              'Brak obiektu!',
//              "Nie można usunąć obiektu " . $name . ", ponieważ nie ma go w rejestrze!"
//);
//        }
        unset(self::$store[$name]);
    }

    public static function contains($name): bool
    {
        return isset(self::$store[$name]);
    }
}
