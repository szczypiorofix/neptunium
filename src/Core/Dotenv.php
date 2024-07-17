<?php

namespace Neptunium\Core;

use Exception;

class Dotenv {
    private array $registeredKeys = array();

    public function __construct() {}

    /**
     * @throws Exception
     */
    public function load(string $path, array $requiredEnvironmentalKeys = []): void {
        $fileRawContent = @file_get_contents($path);
        if ($fileRawContent) {
            $this->parseEnvFileContent($fileRawContent);
        }
        $this->registerEnvironmentalVariables($requiredEnvironmentalKeys);
    }

    public function unloadVariables(): void {
        foreach($this->registeredKeys as $key) {
            unset($_ENV[$key]);
        }
    }

    public function getRegisteredKeys(): array {
        return $this->registeredKeys;
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

    /**
     * @throws Exception
     */
    private function registerEnvironmentalVariables(
        array $requiredEnvironmentalVariables
    ): void {
        foreach($requiredEnvironmentalVariables as $requiredEnvironmentalVariable) {
            if (!isset($_ENV[$requiredEnvironmentalVariable])) {
//                echo '<pre>';
//                print_r("No required environmental variable: $requiredEnvironmentalVariable");
//                echo '</pre>';
                throw new Exception('Environmental variable "' . $requiredEnvironmentalVariable . '" not found.');
            }
        }
    }
}
