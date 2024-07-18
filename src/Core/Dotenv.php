<?php

namespace Neptunium\Core;

use Exception;

class Dotenv {
    private array $registeredKeys = array();

    public function __construct() {}

    /**
     * @throws Exception
     */
    public function load(string $path): void {
        $fileRawContent = @file_get_contents($path);
        if ($fileRawContent) {
            $this->parseEnvFileContent($fileRawContent);
        }
    }

    public function unloadVariables(): void {
        foreach($this->registeredKeys as $key) {
            unset($_ENV[$key]);
        }
    }

    public function getRegisteredKeys(): array {
        return $this->registeredKeys;
    }

    public function checkRequiredEnvironmentalVariables(array $requiredEnvironmentalVariables): bool {
        $allEnvironmentalVariablesAllAvailable = true;
        foreach($requiredEnvironmentalVariables as $requiredEnvironmentalVariable) {
            if (getenv($requiredEnvironmentalVariable) !== false) {
                $allEnvironmentalVariablesAllAvailable = false;
            }
//            if (!isset($_ENV[$requiredEnvironmentalVariable])) {
//                $allEnvironmentalVariablesAllAvailable = false;
//            }
        }
        return $allEnvironmentalVariablesAllAvailable;
    }

    private function parseEnvFileContent(string $fileRawContent): void {
        $fileContentArray = explode(PHP_EOL, $fileRawContent);
        foreach($fileContentArray as $line) {
            $lineArray = explode("=", trim($line));
            if (isset($lineArray[0]) && isset($lineArray[1])) {
                $lineKey = trim($lineArray[0]);
                $lineValue = trim($lineArray[1]);
                $this->registeredKeys[] = $lineKey;
                $_ENV[$lineKey] = $lineValue;
            }
        }
    }
}
