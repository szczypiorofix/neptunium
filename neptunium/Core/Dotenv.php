<?php

namespace Neptunium\Core;

use Exception;

class Dotenv {
    private array $registeredKeys = array();

    public function __construct() {}

    /**
     * @throws Exception
     */
    public function load(string $path, array $requiredEnvironmentalVariables = []): void {
        $fileRawContent = @file_get_contents($path);
        if (!$fileRawContent) {
            throw new Exception('Unable to read file: ' . $path);
        }
        $fileContentArray = $this->parseContent($fileRawContent);
        $this->registerEnvironmentalVariables(
            $fileContentArray,
            $requiredEnvironmentalVariables
        );
    }

    public function unloadVariables(): void {
        foreach($this->registeredKeys as $key) {
            unset($_ENV[$key]);
        }
    }

    private function parseContent(string $fileRawContent): array {
        return explode(PHP_EOL, $fileRawContent);
    }

    private function registerEnvironmentalVariables(
        array $fileContentArray,
        array $requiredEnvironmentalVariables
    ): void {
        foreach($fileContentArray as $line) {
            $lineArray = explode("=", trim($line));
            if (isset($lineArray[0]) && isset($lineArray[1])) {
                $lineKey = trim($lineArray[0]);
                $lineValue = trim($lineArray[1]);
                $this->registeredKeys[] = $lineKey;
                $_ENV[$lineKey] = $lineValue;
            }
        }

        foreach($requiredEnvironmentalVariables as $requiredEnvironmentalVariable) {
            if (isset($_ENV[$requiredEnvironmentalVariable])) {
                // show warning about required environmental variable not given
            }
        }
    }
}
