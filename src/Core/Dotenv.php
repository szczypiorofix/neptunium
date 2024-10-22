<?php

namespace Neptunium\Core;

use Exception;

class Dotenv {
    private static array $registeredKeys = array();
    private static ?Dotenv $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): Dotenv {
        if (is_null(self::$instance)) {
            self::$instance = new Dotenv();
        }
        return self::$instance;
    }
    
    /**
     * @throws Exception
     */
    public static function load(string $path): void {
        $fileRawContent = @file_get_contents($path);
        if ($fileRawContent) {
            self::parseEnvFileContent($fileRawContent);
        }
    }

    public static function getRegisteredKeys(): array {
        return self::$registeredKeys;
    }

    public function getValue(string $key): mixed {        
        $value = getenv($key);
        if ($value) {
            return $value;
        }
        throw new ModelClasses\FrameworkException("Brak klucza!", "Brak klucza: " . $key);
    }

    private static function parseEnvFileContent(string $fileRawContent): void {
        $fileContentArray = explode(PHP_EOL, $fileRawContent);
        foreach($fileContentArray as $line) {
            $lineArray = explode("=", trim($line));
            if (isset($lineArray[0]) && isset($lineArray[1])) {
                $lineKey = trim($lineArray[0]);
                $lineValue = trim($lineArray[1]);
                self::$registeredKeys[$lineKey] = $lineValue;
                putenv("$lineKey=$lineValue");
            }
        }
    }
}
